<?php

namespace EssentialsBundle\Api;

use CollectorBundle\Mapper\Discovery\ApiMapper;
use EssentialsBundle\Cache\CacheFactory;
use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Entity\Account\AccountInterface;
use GearmanBundle\TaskManager\TaskManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Guard\AuthenticatorInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

abstract class ControllerAbstract extends Controller
{

    /**
     * @var GuardAuthenticatorHandler
     */
    protected $guardHandler = null;

    /**
     * @var AuthenticatorInterface
     */
    protected $formAuthenticator = null;

    /**
     * @var CacheFactory
     */
    protected $cacheFactory = null;

    /**
     * @var TaskManager
     */
    protected $taskManager = null;

    /**
     * @var ApiMapper
     */
    protected $apiMapper = null;

    public function getGuardHandler()
    {
        return $this->guardHandler ?? $this->get('security.authentication.guard_handler');
    }

    /**
     * @param GuardAuthenticatorHandler $guardHandler
     * @required
     */
    public function setGuardHandler(GuardAuthenticatorHandler $guardHandler)
    {
        $this->guardHandler = $guardHandler;
    }

    /**
     * @return mixed
     */
    public function getFormAuthenticator()
    {
        return $this->formAuthenticator ?? $this->get('app.security.login_form_authenticator');
    }

    /**
     * @param AuthenticatorInterface $formAuthenticator
     * @required
     */
    public function setFormAuthenticator(AuthenticatorInterface $formAuthenticator): void
    {
        $this->formAuthenticator = $formAuthenticator;
    }

    public function userHasRole(string $role, AccountInterface $account = null): bool
    {
        return ($this->get('security.authorization_checker')->isGranted($role, $account));
    }

    /**
     * @param Account $account
     * @param Request $request
     * @return $this
     */
    public function loginUser(Account $account, Request $request)
    {
        $this->getGuardHandler()->authenticateUserAndHandleSuccess(
            $account,
            $request,
            $this->getFormAuthenticator(),
            'main'
        );

        return $this;
    }

    /**
     * @return CacheFactory
     */
    public function getCacheFactory()
    {
        return $this->cacheFactory ?? $this->get('cache.factory');
    }

    /**
     * @param CacheFactory $cacheFactory
     */
    public function setCacheFactory(CacheFactory $cacheFactory): void
    {
        $this->cacheFactory = $cacheFactory;
    }

    /**
     * @return TaskManager
     */
    public function getTaskManager()
    {
        return $this->taskManager ?? $this->get('gearman.taskmanager');
    }

    /**
     * @param TaskManager $taskManager
     */
    public function setTaskManager(TaskManager $taskManager): void
    {
        $this->taskManager = $taskManager;
    }

    /**
     * @return ApiMapper
     */
    public function getApiMapper()
    {
        return $this->apiMapper ?? $this->get('discovery.api.mapper');
    }

    /**
     * @param ApiMapper $apiMapper
     */
    public function setApiMapper(ApiMapper $apiMapper): void
    {
        $this->apiMapper = $apiMapper;
    }

    /**
     * @param $data
     * @param int $statusCode
     * @param array $headers
     * @return JsonResponse
     */
    protected function createApiResponse(
        $data,
        int $statusCode = Response::HTTP_OK,
        $headers = []
    ): JsonResponse {
        return new JsonResponse(
            $data,
            $statusCode,
            array_merge(
                $headers,
                [
                    'Content-Type' => 'application/json',
                    'Content-Language' => 'en',
                ]
            )
        );
    }

    /**
     * @param int $exceptionCode
     * @param array $errors
     * @param array $extraData
     * @return JsonResponse
     */
    protected function throwApiProblemResponse(
        int $exceptionCode,
        array $errors = [],
        array $extraData = []
    ): JsonResponse {
        $extraData = array_merge($extraData, [ 'errors' => $errors ]);
        $apiProblem = new ApiProblem(
            $exceptionCode,
            $extraData
        );

        throw new ApiProblemException($apiProblem, null, [], $exceptionCode);
    }
}
