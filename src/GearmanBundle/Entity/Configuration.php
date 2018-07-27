<?php

namespace GearmanBundle\Entity;

use GearmanBundle\Collection\Configuration\JobServerCollection;
use GearmanBundle\Collection\Configuration\WorkerCollection;
use EssentialsBundle\Entity\EntityAbstract;

/**
 * Class Configuration
 * @package GearmanBundle\Entity
 */
class Configuration extends EntityAbstract
{
    /**
     * @var WorkerCollection
     */
    protected $workers;

    /**
     * @var JobServerCollection
     */
    protected $jobServers;

    /**
     * Configuration constructor.
     * @param WorkerCollection $workers
     * @param JobServerCollection $jobServers
     */
    public function __construct(WorkerCollection $workers, JobServerCollection $jobServers)
    {
        $this->workers = $workers;
        $this->jobServers = $jobServers;
    }

    /**
     * @return WorkerCollection
     */
    public function getWorkers(): WorkerCollection
    {
        return $this->workers;
    }

    /**
     * @return JobServerCollection
     */
    public function getJobServers(): JobServerCollection
    {
        return $this->jobServers;
    }
}
