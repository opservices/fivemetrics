<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 18/05/17
 * Time: 15:36
 */

namespace EssentialsBundle\EventListener;

use EssentialsBundle\Api\ApiProblem;
use EssentialsBundle\Api\ApiProblemException;
use EssentialsBundle\Exception\Dispatcher;
use Exception;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber implements EventSubscriberInterface
{
    private $debug;

    /**
     * @var Dispatcher
     */
    protected $dispatcher;

    public function __construct($debug, Dispatcher $errorDispatcher)
    {
        $this->debug = $debug;
        $this->dispatcher = $errorDispatcher;
    }

    public function processException(GetResponseForExceptionEvent $event)
    {
        $e = $event->getException();
        $statusCode = $e->getCode();

        // allow 500 errors or default PHP error code to be thrown
        if (($this->debug) && (($statusCode == 0) || ($statusCode >= 500))) {
            return;
        }

        $apiProblem = $this->getApiProblemInstance($e);
        $data = $apiProblem->toArray();
        // making type a URL, to a temporarily fake page
        if ($data['type'] != 'about:blank') {
            $data['type'] = 'http://localhost:8000/docs/errors#'.$data['type'];
        }

        $response = new JsonResponse(
            $data,
            $apiProblem->getStatusCode()
        );
        $response->headers->set('Content-Type', 'application/problem+json');
        $response->headers->set('Content-Language', 'en');

        $event->setResponse($response);
    }

    protected function getApiProblemInstance(Exception $e)
    {
        $code = $e->getCode();

        if ($e instanceof ApiProblemException) {
            return $e->getApiProblem();
        }

        $apiProblem = new ApiProblem($code);

        if (($code > 0) && ($code < 500)) {
            $apiProblem->set('detail', $e->getMessage());
        }

        return  $apiProblem;
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
                ['processException', 10],
                ['logException', 0],
            ],
        ];
    }
}
