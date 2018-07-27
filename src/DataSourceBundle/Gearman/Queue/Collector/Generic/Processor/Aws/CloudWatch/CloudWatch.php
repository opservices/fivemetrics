<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 21/02/18
 * Time: 08:40
 */

namespace DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\CloudWatch;

use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\CloudWatch\Job\Job;
use DataSourceBundle\Gearman\Queue\Collector\Generic\ProcessorInterface;
use DataSourceBundle\Gearman\Queue\Collector\Generic\ResultSet;
use GearmanBundle\Job\JobInterface;
use DataSourceBundle\Aws\CloudWatch\CloudWatch as CloudWatchClient;

class CloudWatch implements ProcessorInterface
{
    /**
     * @var Job
     */
    protected $job;

    public function __destruct()
    {
        $this->job = null;
    }

    /**
     * @return Job
     */
    public function getJob(): Job
    {
        return $this->job;
    }

    public function setJob(JobInterface $job): ProcessorInterface
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
    public function isValidJob($job): bool
    {
        return (is_a($job, Job::class));
    }

    public function process(ResultSet $resultSet): ResultSet
    {
        $job = $this->getJob();
        $metricStatistic = $job->getMetricStatistic();

        $metrics = (new CloudWatchClient(
            $job->getKey(),
            $job->getSecret(),
            $job->getRegion()
        ))->getMetricStatistics($metricStatistic);

        $resultSet->setMetrics($metrics);

        return $resultSet;
    }
}
