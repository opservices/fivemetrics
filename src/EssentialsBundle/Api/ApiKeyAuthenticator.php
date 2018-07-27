<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 26/01/18
 * Time: 08:14
 */

namespace EssentialsBundle\Api;

use Doctrine\ORM\EntityManagerInterface;
use EssentialsBundle\Entity\Account\Account;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class ApiKeyAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var EntityManagerInterface
     */
    protected $em = null;

    /**
     * ApiKeyAuthenticator constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning false will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request)
    {
        return $request->headers->has('X-AUTH-KEY');
    }

    /**
     * Called on every request. Return whatever credentials you want to
     * be passed to getUser() as $credentials.
     */
    public function getCredentials(Request $request)
    {
        return $request->headers->get('X-AUTH-KEY');
    }

    public function getUser($apiKey, UserProviderInterface $userProvider)
    {
        if (null === $apiKey) {
            return;
        }

        return $this->em->getRepository(Account::class)
            ->findOneBy([
                'apiKey' => $apiKey,
            ]);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // no credential check is needed in this case
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
    }

    /**
     * Called when authentication is needed, but it's not sent
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $apiProblem = new ApiProblem(JsonResponse::HTTP_FORBIDDEN);
        $apiProblem->set('detail', [
            'errors' => [
                'You must be logged to perform this action.'
            ]
        ]);

        $data = $apiProblem->toArray();

        $response = new JsonResponse(
            $data,
            JsonResponse::HTTP_FORBIDDEN
        );

        $response->headers->set('Content-Type', 'application/problem+json');
        $response->headers->set('Content-Language', 'en');

        return $response;
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
