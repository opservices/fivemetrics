<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 06/06/17
 * Time: 10:04
 */

namespace DatabaseBundle\Gearman\Queue;

use GearmanBundle\Job\JobInterface;
use GearmanBundle\Worker\WorkerAbstract;

/**
 * Class QueueAbstract
 * @package DatabaseBundle\Gearman\Queue
 */
abstract class QueueAbstract extends WorkerAbstract
{
    /**
     * @var JobInterface
     */
    protected $job;

    /**
     * @return mixed
     */
    public function getJob(): JobInterface
    {
        return $this->job;
    }

    /**
     * @param mixed $job
     * @return QueueAbstract
     */
    public function setJob(JobInterface $job): QueueAbstract
    {
        if (! $this->isValidJob($job)) {
            throw new \InvalidArgumentException(
                "An invalid job has been provided."
            );
        }

        $this->job = $job;
        return $this;
    }

    /**
     * @param $job
     * @return bool
     */
    public function isValidJob(JobInterface $job): bool
    {
        $class = $this->getJobType();
        return ($job instanceof $class);
    }

    abstract public function getJobType(): string;
}
