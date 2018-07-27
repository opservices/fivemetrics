<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 01/03/17
 * Time: 08:36
 */

namespace GearmanBundle\Worker\Manager;

use GearmanBundle\Collection\Worker\Process\ProcessCollection;
use EssentialsBundle\Entity\EntityAbstract;
use GearmanBundle\Entity\Configuration\Worker as WorkerConfiguration;

/**
 * Class Queue
 * @package Gearman\Worker\Manager
 */
class Queue extends EntityAbstract
{
    /**
     * @var WorkerConfiguration
     */
    protected $configuration;

    /**
     * @var ProcessCollection
     */
    protected $processes;

    /**
     * Queue constructor.
     * @param WorkerConfiguration $configuration
     * @param ProcessCollection|null $processes
     */
    public function __construct(
        WorkerConfiguration $configuration,
        ProcessCollection $processes = null
    ) {
        $this->setConfiguration($configuration)
            ->setProcesses(
                (is_null($processes)) ? new ProcessCollection() : $processes
            );
    }

    /**
     * @return WorkerConfiguration
     */
    public function getConfiguration(): WorkerConfiguration
    {
        return $this->configuration;
    }

    /**
     * @param WorkerConfiguration $configuration
     * @return Queue
     */
    public function setConfiguration(WorkerConfiguration $configuration): Queue
    {
        $this->configuration = $configuration;
        return $this;
    }

    /**
     * @return ProcessCollection
     */
    public function getProcesses(): ProcessCollection
    {
        return $this->processes;
    }

    /**
     * @param ProcessCollection $processes
     * @return Queue
     */
    public function setProcesses(ProcessCollection $processes): Queue
    {
        $this->processes = $processes;
        return $this;
    }
}
