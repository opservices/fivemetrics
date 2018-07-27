<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 13/11/17
 * Time: 17:22
 */

namespace FrontendBundle\Controller\Api\V1;

use Aws\Exception\AwsException;
use CollectorBundle\Controller\Api\V1\DiscoveryController;
use DataSourceBundle\Aws\ClientAbstract;
use DataSourceBundle\Aws\EC2\EC2;
use DataSourceBundle\Aws\Glacier\Glacier;
use DataSourceBundle\Aws\S3\S3;
use DataSourceBundle\Entity\Aws\Region\Virginia;
use EssentialsBundle\Api\ControllerAbstract;
use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\Exception\Exceptions;
use EssentialsBundle\Profiler\Enum;
use EssentialsBundle\Profiler\Profiler;
use GearmanBundle\Job\Job;
use GearmanBundle\TaskManager\TaskManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Swagger\Annotations as SWG;

/**
 * Class OnboardingController
 * @package FrontendBundle\Controller
 * @Route("/onboarding")
 */
class OnboardingController extends ControllerAbstract
{
    /**
     * @param Request $request
     * @Route("/discovery/{id}", name="onboarding_discovery_status", requirements={"id": "[^/]+\/?"})
     * @Method("GET")
     * @return
     * @SWG\Get(
     *     summary="Retrieve the discovery status.",
     *     @SWG\Parameter(
     *          required=true,
     *          name="id",
     *          in="path",
     *          type="string",
     *          description="It's the discovery id returned by a successful POST in onboarding discovery endpoint."
     *     ),
     *     @SWG\Response(
     *          response=200,
     *          description="It will return the current discovery status.
    <br>The returned states are ""queued"", ""running"" or ""unknown"".",
     *          @SWG\Schema(
     *              @SWG\Property(property="id", type="string"),
     *              @SWG\Property(property="status", type="string")
     *          ),
     *     )
     * )
     */
    public function discoveryStatusAction($id)
    {
        $controller = new DiscoveryController();
        $controller->setContainer($this->container);
        return $controller->discoveryStatusAction($id);
    }

    /**
     * @param Request $request
     * @Route("/discovery", name="onboarding_discovery_start")
     * @Method("POST")
     * @return Response
     * @SWG\Post(
     *     summary="To start the onboarding discovery.",
     *     @SWG\Parameter(
     *          required=true,
     *          in="body",
     *          name="data sources",
     *          type="string",
     *          description="A JSON string representing a data source list. The parameters aren't mandatory.
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
     *          description="The discovery was queued.",
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
        // @TODO Refactory this code to remove copy and paste problem. We have almost the same code on DiscoveryController:startDiscoveryAction()

        /** @var Account $account */
        $account = $this->getUser();

        if (! $this->userHasRole('ROLE_ALLOW_ONBOARDING', $account)) {
            return $this->throwApiProblemResponse(
                Exceptions::ACCESS_DENIED,
                ["You aren't allowed to perform this action."]
            );
        }

        $requestParams = json_decode($request->getContent(), true);
        if (empty($requestParams)) {
            $this->throwApiProblemResponse(
                Exceptions::VALIDATION_ERROR,
                [ 'An invalid request data has been provided.' ]
            );
        }

        $uid = sprintf("%f%s", microtime(true), $account->getUid());
        $eventUid = md5($uid);

        $profiler = Profiler::createFrom(
            $account,
            $eventUid,
            null,
            Enum::ONBOARDING,
            true
        );

        $time = new DateTime();

        $bucket = $this->get('discovery.api.mapper')
            ->toDiscoveryCollectBucket(
                $account,
                $time,
                $requestParams,
                Profiler::createFrom(
                    $account,
                    $eventUid,
                    Enum::ONBOARDING,
                    Enum::COLLECT
                )
            );

        $job = new Job($account, $time, $bucket, $profiler);

        $uid = Enum::DISCOVERY . '-' . md5($uid);

        $handler = $this->get('gearman.taskmanager')
            ->runBackground(
                'onboarding',
                serialize($job),
                TaskManager::NORMAL,
                $uid
            );

        $profiler->disableEvents();

        $this->get('cache.factory')
            ->factory($account, 'local_cache')
            ->save($uid, $handler);

        return $this->createApiResponse(
            [ 'id' => $uid, 'status' => 'queued', ],
            Response::HTTP_CREATED
        );
    }

    /**
     * @param Request $request
     * @param DataSources[] $services Services collection Optional
     *
     * @Route("/check-credentials/", name="check_credentials")
     * @Method("POST")
     * @SWG\Post(
     *     summary="Check if a given aws credentials is valid",
     *     @SWG\Parameter(
     *          required=true,
     *          in="body",
     *          name="AWS Credentials",
     *          description="Aws key and Secret<br><br>Example:<code>{""aws.key"": ""my_aws_key"", ""aws.secret"": ""my_aws_secret""}</code><br>",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="aws.key",
     *                  type="string",
     *              ),
     *              @SWG\Property(
     *                  property="aws.secret",
     *                  type="string"
     *              )
     *          )
     *     ),
     *     @SWG\Response(
     *          response=200,
     *          description="Returned when the credential is valid",
     *     ),
     *     @SWG\Response(
     *          response=400,
     *          description="Returned when the credential is invalid."
     *     )
     * )
     * @return Response
     */
    public function checkCredentialsAction(Request $request, array $services = null)
    {
        $credential = json_decode($request->getContent(), true);
        $region = new Virginia();
        $key = $credential['aws.key'];
        $secret = $credential['aws.secret'];

        $services = $services ?: [
            'EC2' => new EC2($key, $secret, $region),
            'Glacier' => new Glacier($key, $secret, $region),
            'S3' => new S3($key, $secret, $region),
        ];

        $result = [];

        /** @var  $service ClientAbstract */
        foreach ($services as $label => $service) {
            try {
                $service->checkCredential();
            } catch (AwsException $e) {
                if (in_array($e->getStatusCode(), [Response::HTTP_FORBIDDEN, Response::HTTP_UNAUTHORIZED])) {
                    $result["$label"] = $e->getAwsErrorMessage();
                }
            }
        }

        if (! empty($result)) {
            return $this->throwApiProblemResponse(
                Exceptions::VALIDATION_ERROR,
                $result
            );
        }

        return $this->createApiResponse("Credential is OK", Response::HTTP_OK);
    }
}
