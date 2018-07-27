<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 01/04/18
 * Time: 22:20
 */

namespace EssentialsBundle\EventListener;

use EssentialsBundle\Exception\Dispatcher;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class AuthExceptionSubscriber implements EventSubscriberInterface
{
    /**
     * @var Dispatcher
     */
    protected $dispatcher;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * AuthExceptionSubscriber constructor.
     * @param LoggerInterface $logger
     * @param RouterInterface $router
     */
    public function __construct(Dispatcher $errorDispatcher, RouterInterface $router)
    {
        $this->dispatcher = $errorDispatcher;
        $this->router = $router;
    }

    public function processException(GetResponseForExceptionEvent $event)
    {
        if (!$this->isSecurityException($event->getException())) {
            return;
        }

        $response = ($this->isNeedRedirectToLogin($event))
            ? new RedirectResponse($this->router->generate('security_login'))
            : new JsonResponse(
                [
                    'type' => 'access_denied',
                    'title' => 'Access denied',
                    'status' => JsonResponse::HTTP_FORBIDDEN,
                    'errors' => ['Access denied'],
                ],
                JsonResponse::HTTP_FORBIDDEN,
                [
                    'Content-Type' => 'application/problem+json',
                    'Content-Language' => 'en'
                ]
            );

        $event->setResponse($response);
    }

    protected function isSecurityException(Exception $e)
    {
        return (($e instanceof AuthenticationException)
            || ($e instanceof AuthenticationCredentialsNotFoundException)
            || ($e instanceof AccessDeniedException));
    }

    protected function isNeedRedirectToLogin(GetResponseForExceptionEvent $event): bool
    {
        $request = $event->getRequest();

        if ($request->isXmlHttpRequest()) {
            return false;
        }

        if (preg_match("/^\/api/", $request->getRequestUri())) {
            return false;
        }

        return true;
    }

    public function logException(GetResponseForExceptionEvent $event)
    {
        $this->dispatcher->send(
            $event->getException(),
            null,
            Logger::ERROR
        );
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => [
                ['processException', 30],
                ['logException', 20],
            ],
        ];
    }
}
