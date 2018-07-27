<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 04/09/17
 * Time: 16:06
 */

namespace DataSourceBundle\Controller\Api\V1;

use CollectorBundle\Controller\Api\V1\DiscoveryController;
use DataSourceBundle\Entity\DataSource\DataSource;
use DataSourceBundle\Entity\DataSource\DataSourceGroup;
use DataSourceBundle\Entity\DataSource\DataSourceParameter;
use EssentialsBundle\Api\ControllerAbstract;
use EssentialsBundle\Cache\CacheFactory;
use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\Exception\Exceptions;
use EssentialsBundle\Profiler\Enum;
use GearmanBundle\Job\Job;
use GearmanBundle\TaskManager\TaskManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DataSourceController
 * @package DataSourceBundle\Controller\Api\V1
 * @Route("/datasource")
 */
class DataSourceController extends ControllerAbstract
{
    /**
     * @Route("/", name="apiListDataSources")
     * @Method({"GET"})
     * @SWG\Get(
     *     summary="Retrieve a list with all data sources.",
     *     @SWG\Response(
     *          response=200,
     *          description="It will return an array with the data source properties.<br>
    Example: <br><br>
    [{""name"":""aws.ec2"",""label"":""EC2"",""description"":""AWS EC2 description..."",""icon"":""icon"",""groups"":[{""name"":""aws.management""}],""parameters"":[{""name"":""aws.key"",""label"":""Key"",""description"":""Aws Key...""},{""name"":""aws.secret"",""label"":""Secret"",""description"":""Aws Secret...""},{""name"":""aws.region"",""label"":""Region"",""description"":""Aws region...""}]}]
    "
     *     )
     * )
     */
    public function listDataSourcesAction()
    {
        $dataSources = $this->get('doctrine')
            ->getRepository(DataSource::class)
            ->findAll();

        $response = [];

        /** @var DataSource $ds */
        foreach ($dataSources as $ds) {
            $parameters = array_map(function (DataSourceParameter $param) {
                return [
                    'name' => $param->getName(),
                    'label' => $param->getLabel(),
                    'description' => $param->getDescription(),
                ];
            }, $ds->getParameters()->toArray());

            $groups = array_map(function (DataSourceGroup $group) {
                return [ 'name' => $group->getName() ];
            }, $ds->getGroups()->toArray());

            $response[] = [
                'name' => $ds->getName(),
                'label' => $ds->getLabel(),
                'description' => $ds->getDescription(),
                'icon' => $ds->getIcon(),
                'groups' => $groups,
                'parameters' => $parameters,
            ];
        }

        return $this->createApiResponse($response);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/execute/", name="data_source_execute_action")
     * @Method("POST")
     * @SWG\Post(
     *     summary="To execute a data source.",
     *     @SWG\Parameter(
     *          required=true,
     *          in="body",
     *          name="data sources",
     *          type="string",
     *          description="A JSON string representing a data source list. The parameters property aren't mandatory.
    If a needed parameter isn't provided, it will be researched in account configuration.
    <br><br>Example:
    <code>{""dataSource"":{""name"":""aws.ec2""},""parameters"":[{""name"":""aws.key"",""value"": ""<key>""},{""name"":""aws.secret"",""value"":""<secret>""},{""name"":""aws.region"",""value"": ""<region code>""}]}</code><br>",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="dataSource",
     *                  type="object",
     *                  @SWG\Schema(
     *                      @SWG\Property(
     *                          property="name",
     *                          type="string"
     *                      ),
     *                  ),
     *              ),
     *              @SWG\Property(
     *                  property="parameters",
     *                  type="array"
     *              )
     *          )
     *     ),
     *     @SWG\Response(
     *          response=201,
     *          description="The execution was queued.",
     *          @SWG\Schema(
     *              @SWG\Property(property="id", type="string"),
     *              @SWG\Property(property="status", type="string"),
     *          )
     *     ),
     *     @SWG\Response(
     *          response=400,
     *          description="If an invalid data has been provided."
     *     )
     * )
     */
    public function executeDataSourceAction(Request $request)
    {
        $requestParams = json_decode($request->getContent(), true);
        $requestParams = is_array($requestParams) ? $requestParams : [];

        if (empty($requestParams)) {
            $this->throwApiProblemResponse(
                Exceptions::VALIDATION_ERROR,
                [ 'An invalid request data has been provided.' ]
            );
        }

        /** @var Account $account */
        $account = $this->getUser();
        $time = new DateTime();
        $bucket = $this->getApiMapper()
            ->toDiscoveryCollectBucket($account, $time, $requestParams);

        $job = new Job($account, $time, $bucket);
        $uid = Enum::EXECUTION . '-' . md5(microtime(true) . $account->getUid());

        $handler = $this->getTaskManager()
            ->runBackground('discovery', serialize($job), TaskManager::NORMAL, $uid);

        $this->getCacheFactory()
            ->factory($account, 'local_cache')
            ->save($uid, $handler);

        return $this->createApiResponse(
            [ 'id' => $uid, 'status' => 'queued', ],
            Response::HTTP_CREATED
        );
    }

    /**
     * @param $id
     * @return Response
     * @Route("/execution/{id}", name="data_source_execution_status", requirements={"id": "[^/]+\/?"})
     * @Method("GET")
     * @SWG\Get(
     *     summary="Retrieve the discovery status.",
     *     @SWG\Parameter(
     *          required=true,
     *          name="id",
     *          in="path",
     *          type="string",
     *          description="It's the execution id returned by a successful POST in data source execution endpoint."
     *     ),
     *     @SWG\Response(
     *          response=200,
     *          description="It will return the current execution status.
    <br>The returned states are ""queued"", ""running"", ""unknown"" or ""finished"".",
     *          @SWG\Schema(
     *              @SWG\Property(property="id", type="string"),
     *              @SWG\Property(property="status", type="string"),
     *              @SWG\Property(property="result", type="string"),
     *          ),
     *     )
     * )
     */
    public function executionStatusAction($id)
    {
        $controller = new DiscoveryController();
        $controller->setContainer($this->container);
        return $controller->discoveryStatusAction($id);
    }
}
