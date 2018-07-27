<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 18/10/17
 * Time: 13:45
 */

namespace CollectorBundle\Controller\Api\V1;

use CollectorBundle\Collect\CollectBucket;
use EssentialsBundle\Api\ControllerAbstract;
use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\Exception\Exceptions;
use EssentialsBundle\Profiler\Enum;
use EssentialsBundle\Profiler\Profiler;
use GearmanBundle\Job\Job;
use GearmanBundle\Job\Status;
use GearmanBundle\TaskManager\TaskManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;

/**
 * Class DiscoveryController
 * @package DataSourceBundle\Controller\Api\V1
 * @Route("/discovery")
 */
class DiscoveryController extends ControllerAbstract
{
    /**
     * @param int $id
     * @return Response
     * @Route("/{id}", name="discovery_status", requirements={"id": "[^/]+\/?"})
     * @Method("GET")
     * @SWG\Get(
     *     summary="Retrieve the discovery status.",
     *     @SWG\Parameter(
     *          required=true,
     *          name="id",
     *          in="path",
     *          type="string",
     *          description="It's the discovery id returned by a successful POST in discovery endpoint."
     *     ),
     *     @SWG\Response(
     *          response=200,
     *          description="It will return the current discovery status.
    <br>The returned states are ""queued"", ""running"", ""unknown"" or ""finished"".",
     *          @SWG\Schema(
     *              @SWG\Property(property="id", type="string"),
     *              @SWG\Property(property="status", type="string"),
     *              @SWG\Property(property="result", type="string"),
     *          ),
     *     )
     * )
     */
    public function discoveryStatusAction($id)
    {
        $result = null;
        try {
            $data = $this->getCacheFactory()
                ->factory($this->getUser(), 'local_cache')
                ->fetch($id);

            $handler = (is_string($data)) ? $data : '';
            $status = $this->getTaskManager()->getJobStatus($handler);

            $statusName = $status->getName();
            if ($status->isUnknown() && is_a($data, CollectBucket::class)) {
                $result = $this->mapResult($data);
                $statusName = Status::FINISHED;
            }
        } catch (\Throwable $e) {
            $this->get('error.dispatcher')->send($e, $id);
            $statusName = Status::UNKNOWN;
        }

        return $this->createApiResponse(
            [ 'id' => $id, 'status' => $statusName, 'result' => $result ],
            Response::HTTP_OK
        );
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/", name="start_discovery")
     * @Method("POST")
     * @SWG\Post(
     *     summary="To start a new discovery.",
     *     @SWG\Parameter(
     *          required=true,
     *          in="body",
     *          name="data sources",
     *          type="string",
     *          description="A JSON string representing a data source list. The parameters property aren't mandatory.
    If a needed parameter isn't provided, it will be researched in account configuration.
    <br><br>Example:
    <code>[{""dataSource"":{""name"":""aws.ec2""},""parameters"":[{""name"":""aws.key"",""value"": ""<key>""},{""name"":""aws.secret"",""value"":""<secret>""},{""name"":""aws.region"",""value"": ""<region code>""}]}]</code><br>",
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
     *          description="The was started.",
     *          @SWG\Schema(
     *              @SWG\Property(property="id", type="string")
     *          )
     *     ),
     *     @SWG\Response(
     *          response=400,
     *          description="If an invalid data has been provided."
     *     )
     * )
     */
    public function startDiscoveryAction(Request $request)
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
        $uid = microtime(true) . $account->getUid();
        $eventUid = md5($uid);

        $profiler = Profiler::createFrom($account, $eventUid,null,Enum::DISCOVERY,true);
        $time = new DateTime();

        $bucket = $this->getApiMapper()
            ->toDiscoveryCollectBucket(
                $account,
                $time,
                $requestParams,
                Profiler::createFrom($account, $eventUid, Enum::DISCOVERY,Enum::COLLECT)
            );

        $job = new Job($account, $time, $bucket, $profiler);
        $uid = Enum::DISCOVERY . '-' . md5($uid);

        $this->getTaskManager()->runBackground('discovery', serialize($job),TaskManager::NORMAL, $uid);

        $profiler->disableEvents();

        $this->getCacheFactory()
            ->factory($account, 'local_cache')
            ->save($uid, $this->getTaskManager());

        return $this->createApiResponse(
            [ 'id' => $uid, 'status' => 'queued', ],
            Response::HTTP_CREATED
        );
    }

    /**
     * @param CollectBucket $data
     * @return array
     */
    protected function mapResult(CollectBucket $data): array
    {
        return $data->mapCollects(function ($collect) {
            return [
                'dataSource' => [
                    'name' => $collect['dataSource']['name'],
                ],
                'parameters' => $collect['parameters'],
                'metrics' => $collect['metrics'],
                'errors' => $collect['errors'],
            ];
        });
    }
}
