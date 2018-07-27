<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 01/03/17
 * Time: 18:59
 */

namespace GearmanBundle\TaskManager;

/**
 * Class QueueStatus
 * @package GearmanBundle\TaskManager
 */
class QueueStatus
{
    /**
     * @var string
     */
    protected $queueName;

    /**
     * @var int
     */
    protected $jobsWaiting;

    /**
     * @var int
     */
    protected $jobsRunning;

    /**
     * @var int
     */
    protected $availableWorkers;

    /**
     * QueueStatus constructor.
     * @param string $queueName
     * @param int $jobsWaiting
     * @param int $jobsRunning
     * @param int $availableWorkers
     */
    public function __construct(
        string $queueName,
        int $jobsWaiting,
        int $jobsRunning,
        int $availableWorkers
    ) {
        $this->setQueueName($queueName)
            ->setJobsWaiting($jobsWaiting)
            ->setJobsRunning($jobsRunning)
            ->setAvailableWorkers($availableWorkers);
    }

    /**
     * @return string
     */
    public function getQueueName(): string
    {
        return $this->queueName;
    }

    /**
     * @param string $queueName
     * @return QueueStatus
     */
    public function setQueueName(string $queueName): QueueStatus
    {
        $this->queueName = $queueName;
        return $this;
    }

    /**
     * @return int
     */
    public function getJobsWaiting(): int
    {
        return $this->jobsWaiting;
    }

    /**
     * @param int $jobsWaiting
     * @return QueueStatus
     */
    public function setJobsWaiting(int $jobsWaiting): QueueStatus
    {
        $this->jobsWaiting = $jobsWaiting;
        return $this;
    }

    /**
     * @return int
     */
    public function getJobsRunning(): int
    {
        return $this->jobsRunning;
    }

    /**
     * @param int $jobsRunning
     * @return QueueStatus
     */
    public function setJobsRunning(int $jobsRunning): QueueStatus
    {
        $this->jobsRunning = $jobsRunning;
        return $this;
    }

    /**
     * @return int
     */
    public function getAvailableWorkers(): int
    {
        return $this->availableWorkers;
    }

    /**
     * @param int $availableWorkers
     * @return QueueStatus
     */
    public function setAvailableWorkers(int $availableWorkers): QueueStatus
    {
        $this->availableWorkers = $availableWorkers;
        return $this;
    }
}
