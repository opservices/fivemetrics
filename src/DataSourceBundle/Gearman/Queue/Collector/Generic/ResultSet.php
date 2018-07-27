<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 05/03/17
 * Time: 19:58
 */

namespace DataSourceBundle\Gearman\Queue\Collector\Generic;

use DataSourceBundle\Collection\Gearman\Job\JobCollection;
use DataSourceBundle\Collection\Gearman\Metadata\MetadataCollection;
use EssentialsBundle\Collection\Metric\MetricCollection;
use EssentialsBundle\Entity\Account\AccountInterface;
use EssentialsBundle\Entity\EntityAbstract;

/**
 * Class ResultSet
 * @package DataSourceBundle\Gearman\Queue\Collector\Generic
 */
class ResultSet extends EntityAbstract implements ResultSetInterface
{
    /**
     * @var JobCollection
     */
    protected $jobs;

    /**
     * @var MetricCollection
     */
    protected $metrics;

    /**
     * @var mixed
     */
    protected $data;

    /**
     * @var AccountInterface
     */
    protected $account;

    protected $error;

    /**
     * ResultSet constructor.
     * @param AccountInterface $account
     * @param JobCollection $jobs
     * @param MetricCollection $metrics
     * @param MetadataCollection $data
     * @param null $error
     */
    public function __construct(
        AccountInterface $account,
        JobCollection $jobs,
        MetricCollection $metrics,
        $data,
        $error = null
    ) {
        $this->setAccount($account)
            ->setJobs($jobs)
            ->setMetrics($metrics)
            ->setData($data)
            ->setError($error);
    }

    /**
     * @return AccountInterface
     */
    public function getAccount(): AccountInterface
    {
        return $this->account;
    }

    /**
     * @param AccountInterface $owner
     * @return ResultSet
     */
    public function setAccount(AccountInterface $account): ResultSet
    {
        $this->account = $account;
        return $this;
    }

    public function getError()
    {
        return $this->error;
    }

    /**
     * @param $error
     * @return ResultSet
     */
    public function setError($error): ResultSet
    {
        $this->error = $error;
        return $this;
    }

    /**
     * @return JobCollection
     */
    public function getJobs(): JobCollection
    {
        return $this->jobs;
    }

    /**
     * @param JobCollection $jobs
     * @return ResultSet
     */
    public function setJobs(JobCollection $jobs): ResultSet
    {
        $this->jobs = $jobs;
        return $this;
    }

    /**
     * @return MetricCollection
     */
    public function getMetrics(): MetricCollection
    {
        return $this->metrics;
    }

    /**
     * @param MetricCollection $metrics
     * @return ResultSet
     */
    public function setMetrics(MetricCollection $metrics): ResultSet
    {
        $this->metrics = $metrics;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     * @return ResultSet
     */
    public function setData($data): ResultSet
    {
        $this->data = $data;
        return $this;
    }
}
