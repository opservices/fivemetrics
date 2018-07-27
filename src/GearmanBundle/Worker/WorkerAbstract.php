<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 24/02/17
 * Time: 09:04
 */

namespace GearmanBundle\Worker;

use GearmanBundle\Collection\Configuration\JobServerCollection;
use GearmanBundle\Collection\Worker\QueueCollection;
use EssentialsBundle\Exception\Dispatcher;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Monolog\Logger;

/**
 * Class WorkerAbstract
 * @package Gearman\Worker
 */
abstract class WorkerAbstract implements WorkerInterface
{
    const EXIT_CODE_FAILURE = 1;

    /**
     * @var array $queues Are an associative array with "Queue name" => "method name"
     */
    protected $queues;

    /**
     * @var array $registered_functions stores all methods that we need register
     * for a worker.
     */
    protected $registeredFunctions = [];

    /**
     * @var JobServerCollection $job_servers is a list with all job servers address.
     */
    protected $jobServers;

    /**
     * @var \GearmanWorker $worker is a instance of GearmanWorker class.
     */
    protected $worker;

    /**
     * @var Integer $io_timeout is the maximum time spent for a worker with IO.
     */
    protected $ioTimeout = 10000; // in milliseconds

    protected $configuration = null;

    /**
     * @var LoggerInterface
     */
    protected $logger = null;

    /**
     * @var Dispatcher
     */
    protected $errorDispatcher;

    /**
     * WorkerAbstract constructor.
     * @param JobServerCollection|null $jobServers
     * @param \GearmanWorker|null $worker
     * @param null $configuration
     * @param Dispatcher|null $errorDispatcher
     */
    public function __construct(
        JobServerCollection $jobServers = null,
        \GearmanWorker $worker = null,
        $configuration = null,
        Dispatcher $errorDispatcher = null
    ) {
        $this->setWorker((is_null($worker)) ? new \GearmanWorker() : $worker);
        (is_null($jobServers)) ?: $this->setJobServers($jobServers);
        (is_null($configuration)) ?: $this->setConfiguration($configuration);
        $this->errorDispatcher = (is_null($errorDispatcher))
            ? new Dispatcher() : $errorDispatcher;
    }

    /**
     * @param $configuration
     * @return WorkerInterface
     */
    public function setConfiguration($configuration): WorkerInterface
    {
        $this->configuration = $configuration;
        return $this;
    }

    /**
     * The setWorker method is used to overwrite original instance of the
     * GearmanWorker.
     * @param \GearmanWorker $worker
     * @return WorkerInterface
     */
    protected function setWorker(\GearmanWorker $worker): WorkerInterface
    {
        unset($this->worker);
        $this->worker = $worker;
        return $this;
    }

    /**
     * This method returns a copy of the local GearmanWorker instance.
     * @return \GearmanWorker
     */
    protected function getWorker(): \GearmanWorker
    {
        return $this->worker;
    }

    /**
     * @return int
     */
    public function getIoTimeout(): int
    {
        return $this->ioTimeout;
    }

    /**
     * This method is used to define the maximum time spent for a worker with IO
     * in milliseconds
     * @param Integer $timeout
     *     Is the maximum time spent for a worker with IO in milliseconds.
     * @throw InvalidArgumentException
     *     If the timeout is lower than zero.
     * @return WorkerInterface
     */
    public function setIoTimeout(int $timeout): WorkerInterface
    {
        if ($timeout < 0) {
            throw new \InvalidArgumentException(
                "An invalid timeout has been provided."
            );
        }

        $this->ioTimeout = $timeout;
        return $this;
    }

    /**
     * @return QueueCollection
     */
    abstract protected function getQueues(): QueueCollection;

    /**
     * This method is used to register all queues and methods for a worker. The
     * registered method is called with a GearmanJob parameter.
     * @throw InvalidArgumentException
     *     If the $method_names is empty.
     */
    protected function registerMethods()
    {
        $queues = $this->getQueues();

        if (count($queues) <= 0) {
            throw new \InvalidArgumentException(
                "There must be at least one method to be registered."
            );
        }

        foreach ($queues as $queue) {
            $this->worker->addFunction(
                $queue->getName(),
                [ $this, $queue->getMethod() ]
            );
        }

        return $this;
    }

    /**
     * This method is called to configure worker's servers and check if there is
     * registered methods.
     * @throw InvalidWorkerException
     *     If the worker doesn't have registered methods.
     *     If the worker doesn't have job servers.
     */
    protected function prepareWorker()
    {
        $this->registerMethods();

        if (count($this->getJobServers()) <= 0) {
            throw new \RuntimeException(
                "Trying start a worker without a job server."
            );
        }

        $this->worker->setTimeout($this->getIoTimeout());
        $this->worker->addServers($this->getJobServers());

        return $this;
    }

    /**
     * This method must be called to start the worker execution.
     * @codeCoverageIgnore
     */
    public function run()
    {
        $this->prepareWorker();

        while (true) {
            try {
                $this->worker->work();
            } catch (\Throwable $e) {
                $this->errorDispatcher->send(
                    $e,
                    $this->worker->error(),
                    Logger::CRITICAL
                );
                exit(self::EXIT_CODE_FAILURE);
            }
        }
    }

    /**
     * @return JobServerCollection
     */
    public function getJobServers(): JobServerCollection
    {
        return $this->jobServers;
    }

    /**
     * @param JobServerCollection $jobServers
     * @return WorkerInterface
     */
    public function setJobServers(JobServerCollection $jobServers): WorkerInterface
    {
        if (count($jobServers) <= 0) {
            throw new \InvalidArgumentException(
                "An empty job server's list has been provided."
            );
        }

        $this->jobServers = $jobServers;
        return $this;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @param LoggerInterface $logger
     * @return WorkerInterface
     */
    public function setLogger(LoggerInterface $logger): WorkerInterface
    {
        $this->logger = $logger;
        $this->errorDispatcher->setLogger($logger);
        return $this;
    }
}
