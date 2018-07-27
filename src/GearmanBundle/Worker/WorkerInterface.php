<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 24/02/17
 * Time: 09:00
 */

namespace GearmanBundle\Worker;

use GearmanBundle\Collection\Configuration\JobServerCollection;
use Psr\Log\LoggerInterface;

/**
 * A interface for a gearman worker.
 */
interface WorkerInterface
{
    /**
     * This method is used to define the maximum time spent for a worker with IO
     * in milliseconds
     *
     * @param Integer $timeout Is the maximum time spent for a worker with IO
     * in milliseconds.
     */
    public function setIoTimeout(int $timeout): WorkerInterface;

    /**
     * This method must be called to start the worker execution.
     */
    public function run();

    /**
     * This method is used to define a list of job servers.
     *
     * @param JobServerCollection $servers
     *     Is a list of the job servers address where this worker need runs.
     */
    public function setJobServers(JobServerCollection $servers): WorkerInterface;

    /**
     * @param $configuration
     * @return WorkerInterface
     */
    public function setConfiguration($configuration): WorkerInterface;

    /**
     * @param LoggerInterface $
     * @return WorkerInterface
     */
    public function setLogger(LoggerInterface $logger): WorkerInterface;
}
