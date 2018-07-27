<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 01/03/17
 * Time: 17:10
 */

namespace GearmanBundle\TaskManager;

use GearmanBundle\Collection\TaskManager\QueueStatusCollection;
use GearmanBundle\Configuration\Loader;
use GearmanBundle\Configuration\LoaderInterface;
use EssentialsBundle\FunctionCaller;
use GearmanBundle\Job\Status;

/**
 * Class TaskManager
 * @package Gearman
 */
class TaskManager
{
    /**
     * Is the normal priority for a gearman task.
     * @const NORMAL
     */
    const NORMAL = 0;

    /**
     * Is the high priority for a gearman task.
     * @const HIGH
     */
    const HIGH = 1;

    /**
     * Is the very high priority for a gearman task.
     * @const VERY_HIGH
     */
    const VERY_HIGH = 2;

    /**
     * @var \GearmanClient $client
     */
    protected $client = null;

    /**
     * @var FunctionCaller
     */
    protected $fnCaller = null;

    /**
     * @var LoaderInterface
     */
    protected $confLoader = null;

    /**
     * TaskManager constructor.
     * @param LoaderInterface|null $configurationLoader
     * @param \GearmanClient|null $client
     */
    public function __construct(
        LoaderInterface $configurationLoader = null,
        \GearmanClient $client = null,
        FunctionCaller $fnCaller = null
    ) {
        $this->setClient($client ?: new \GearmanClient());
        $this->confLoader = $configurationLoader ?: Loader::getInstance();
        $this->fnCaller = $fnCaller ?: new FunctionCaller();

        $servers = $this->getConfLoader()->load()->getJobServers();
        $this->getClient()->addServers($servers);
    }

    /**
     * @return LoaderInterface
     */
    protected function getConfLoader(): LoaderInterface
    {
        return $this->confLoader;
    }

    /**
     * @return FunctionCaller
     */
    protected function getFnCaller(): FunctionCaller
    {
        return $this->fnCaller;
    }

    /**
     * This method returns a copy of the local of GearmanClient instance.
     *
     * @return \GearmanClient
     */
    public function getClient(): \GearmanClient
    {
        return $this->client;
    }

    /**
     * @param \GearmanClient $client
     * @return TaskManager
     */
    public function setClient(\GearmanClient $client): TaskManager
    {
        $this->client = $client;
        return $this;
    }

    /**
     * This method send a task for a gearman queue with some priority and wait
     * the result. The priority can by NORMAL (default), HIGH or VERY HIGH.
     *
     * @param string  $queue    Is the queue's name that will used to sent a task.
     * @param mixed   $data     Is the data that will be sent to worker.
     * @param int $priority Is the priority that the task has to be performed.
     *
     * @throw InvalidArgumentException If priority is unknown.
     * @throw RuntimeException If can't submit a task to gearman.
     *
     * @return string Is a string representing the results of running a task.
     */
    public function run(
        string $queue,
        $data,
        int $priority = self::NORMAL
    ) {
        switch ($priority) {
            case self::NORMAL:
                $result = $this->getClient()->doLow($queue, $data);
                break;
            case self::HIGH:
                $result = $this->getClient()->doNormal($queue, $data);
                break;
            case self::VERY_HIGH:
                $result = $this->getClient()->doHigh($queue, $data);
                break;
            default:
                throw new \InvalidArgumentException(
                    "Unknown run priority: " . $priority
                );
        }

        if ($this->getClient()->returnCode() != GEARMAN_SUCCESS) {
            throw new \RuntimeException(
                "Can't submit job to queue \"" . $queue . "\": "
                . $this->getClient()->error()
            );
        }

        return $result;
    }

    /**
     * This method send a task for a gearman queue runs in background with some
     * priority. The priority can by NORMAL (default), HIGH or VERY HIGH.
     *
     * @param string  $queue    Is the queue's name that will used to sent a task.
     * @param mixed   $data     Is the data that will be sent to worker.
     * @param int $priority Is the priority that the task has to be performed.
     *
     * @throw \InvalidArgumentException If priority is unknown.
     * @throw \RuntimeException         If can't submit a task for geaman.
     *
     * @return string The job handle for the submitted task.
     */
    public function runBackground(
        string $queue,
        $data,
        int $priority = self::NORMAL,
        string $unique = null
    ) {
        switch ($priority) {
            case self::NORMAL:
                $handler = $this->getClient()->doLowBackground($queue, $data, $unique);
                break;
            case self::HIGH:
                $handler = $this->getClient()->doBackground($queue, $data, $unique);
                break;
            case self::VERY_HIGH:
                $handler = $this->getClient()->doHighBackground($queue, $data, $unique);
                break;
            default:
                throw new \InvalidArgumentException(
                    "Unknown run priority: " . $priority
                );
        }

        if ($this->getClient()->returnCode() != GEARMAN_SUCCESS) {
            throw new \RuntimeException(
                "Can't submit job to queue \"" . $queue . "\": "
                . self::getClient()->error()
            );
        }

        return $handler;
    }

    /**
     * The getQueueStatus method returns the gearman's queue status, with the
     * queue name, available workers, jobs waiting and running.
     * @param string $address
     * @param int $port
     * @return QueueStatusCollection
     */
    public function getQueueStatus($address = '127.0.0.1', $port = 4730): QueueStatusCollection
    {
        $sock = $this->openSocket($address, $port);
        $this->sendCommand($sock, 'status');

        $qStatus = new QueueStatusCollection();

        do {
            $content = $this->getStreamContent($sock);

            if (preg_match("/^\./", $content)) {
                break;
            }

            @list($name, $waiting, $running, $availableWorkers)
                = explode("\t", $content);

            $qStatus->add(
                new QueueStatus(
                    $name,
                    $waiting - $running,
                    $running,
                    $availableWorkers
                )
            );
        } while (true);

        @$this->getFnCaller()->fclose($sock);

        return $qStatus;
    }

    /**
     * The sendCommand is used to send commands into a resource.
     *
     * @param resource $stream  Is the stream used to send the command.
     * @param string   $command to send.
     *
     * @throw \RuntimeException whether $stream isn't a resource.
     */
    protected function sendCommand($stream, $command)
    {
        if ($this->getFnCaller()->is_resource($stream)) {
            fprintf($stream, $command . "\n");
            return;
        }

        throw new \RuntimeException(__FUNCTION__ . ": Resource is invalid.");
    }

    /**
     * This method reads from stream resource (socket).
     *
     * @param resource $stream to get content.
     * @return string
     */
    protected function getStreamContent($stream): string
    {
        return (is_resource($stream)) ? trim(fgets($stream)) : "";
    }

    /**
     * The openSocket method open a socket with Gearman on localhost.
     * @param string $address
     * @param int $port
     * @return resource
     * @throw RuntimeException Whether can't connect on Gearman.
     */
    protected function openSocket($address, $port)
    {
        $sock = @$this->getFnCaller()
            ->fsockopen($address, $port, $err, $errstr, 3);

        if ($sock) {
            @$this->getFnCaller()
                ->stream_set_timeout($sock, 2);
            return $sock;
        }

        throw new \RuntimeException(
            "Can't connect on Gearman using localhost:4730."
        );
    }

    /**
     * Get the status of a background job.
     *
     * @param string $jobHandler Is the same returned by run and runBackground
     * methods.
     *
     * @return Status
     *    An status object containing information for the job corresponding to
     *    the supplied job handle.
     *
     * @see TaskManager::run()
     * @see TaskManager::runBackground()
     */
    public function getJobStatus($jobHandler): Status
    {
        return new Status($this->getClient()->jobStatus($jobHandler));
    }
}
