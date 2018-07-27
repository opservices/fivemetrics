<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 20/05/17
 * Time: 23:29
 */

namespace DataSourceBundle\Gearman\Queue;

use DataSourceBundle\Collection\Gearman\Job\JobCollection;
use DataSourceBundle\Gearman\Queue\Collector\Generic\ResultSet;
use EssentialsBundle\Collection\Metric\MetricCollection;
use EssentialsBundle\Entity\Account\AccountInterface;
use EssentialsBundle\Exception\Exceptions;
use GearmanBundle\Job\JobInterface;
use GearmanBundle\Worker\WorkerAbstract;

/**
 * Class QueueAbstract
 * @package DataSourceBundle\Gearman\Queue
 */
abstract class QueueAbstract extends WorkerAbstract
{
    /**
     * @var ResultSet
     */
    protected $resultSet;

    /**
     * @var JobInterface
     */
    protected $job;

    /**
     * @return JobInterface
     */
    public function getJob(): JobInterface
    {
        return $this->job;
    }

    /**
     * @param mixed $job
     * @return QueueAbstract
     */
    public function setJob($job): QueueAbstract
    {
        if (! $this->isValidJob($job)) {
            throw new \InvalidArgumentException(
                'An invalid job has been provided.',
                Exceptions::VALIDATION_ERROR
            );
        }

        $this->job = $job;
        return $this;
    }

    /**
     * @param AccountInterface $account
     * @param null $error
     * @return ResultSet
     */
    protected function getResultSetInstance(
        AccountInterface $account,
        $error = null
    ): ResultSet {
        if (is_null($this->resultSet)) {
            $this->resultSet = new ResultSet(
                $account,
                new JobCollection(),
                new MetricCollection(),
                null,
                $error
            );
        }

        return $this->resultSet;
    }

    /**
     * @param $job
     * @return bool
     */
    public function isValidJob($job): bool
    {
        $class = $this->getJobType();
        return ($job instanceof $class);
    }

    /**
     * @return string
     */
    abstract public function getJobType(): string;
}
