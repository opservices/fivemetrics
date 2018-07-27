<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 06/03/17
 * Time: 14:53
 */

namespace DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EC2\Job;

use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\EBS\FilterCollection
    as EBSFilterCollection;
use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\FilterCollection;
use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer\FilterCollection
    as ELBFilterCollection;
use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScalingGroup\FilterCollection
    as AutoScalingGroupFilterCollection;
use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\JobCollection;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer\Job\Job as ELBJob;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScaling\Job\Job as AutoScalingJob;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EBS\Job\Job as EBSJob;

/**
 * Class Builder
 * @package DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EC2\Job
 */
class Builder
{
    /**
     * @param Job $baseJob
     * @param FilterCollection $filters
     * @return JobCollection
     */
    public function build(
        Job $baseJob,
        FilterCollection $filters
    ) {
        return $this->buildJobsFromFilters($baseJob, $filters);
    }

    /**
     * @param Job $baseJob
     * @param FilterCollection $filters
     * @return JobCollection
     */
    protected function buildJobsFromFilters(
        Job $baseJob,
        FilterCollection $filters
    ): JobCollection {
        $jobs = new JobCollection();
        $ec2Filters = new FilterCollection();
        $elbFilters = new ELBFilterCollection();
        $aFilters = new AutoScalingGroupFilterCollection();
        $ebsFilters = new EBSFilterCollection();
        foreach ($filters as $filter) {
            switch ($filter->getNamespace()) {
                case 'ElasticLoadBalancer':
                    $job = $this->getELBJobInstance($baseJob);
                    $filters = $elbFilters;
                    $filterClass =
                        'DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer\Filter';
                    break;
                case 'AutoScaling':
                    $job = $this->getAutoScalingJobInstance($baseJob);
                    $filters = $aFilters;
                    $filterClass = 'DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScalingGroup\Filter';
                    break;
                case 'EC2':
                    $job = $this->getEC2JobInstance($baseJob);
                    $filters = $ec2Filters;
                    $filterClass = 'DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\Filter';
                    break;
                case 'EBS':
                    $job = $this->getEBSJobInstance($baseJob);
                    $filters = $ebsFilters;
                    $filterClass = 'DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\EBS\Filter';
                    break;
                default:
                    throw new \InvalidArgumentException(
                        "An unsupported service name has been provided."
                    );
            }

            $filters->add(new $filterClass(
                $filter->getNamespace(),
                $filter->getMeasurementNames()
            ));

            $job->setData(clone($filters));
            $jobs->add($job);

            $filters->clear();
        }

        return $jobs;
    }

    /**
     * @param Job $baseJob
     * @return Job
     */
    protected function getEC2JobInstance(Job $baseJob): Job
    {
        return new Job(
            $baseJob->getAccount(),
            $baseJob->getDateTime(),
            $baseJob->getRegion(),
            $baseJob->getKey(),
            $baseJob->getSecret()
        );
    }

    /**
     * @param Job $baseJob
     * @return AutoScalingJob
     */
    protected function getAutoScalingJobInstance(Job $baseJob): AutoScalingJob
    {
        return new AutoScalingJob(
            $baseJob->getAccount(),
            $baseJob->getDateTime(),
            $baseJob->getRegion(),
            $baseJob->getKey(),
            $baseJob->getSecret()
        );
    }

    /**
     * @param Job $baseJob
     * @return ELBJob
     */
    protected function getELBJobInstance(Job $baseJob): ELBJob
    {
        return new ELBJob(
            $baseJob->getAccount(),
            $baseJob->getDateTime(),
            $baseJob->getRegion(),
            $baseJob->getKey(),
            $baseJob->getSecret()
        );
    }

    /**
     * @param Job $baseJob
     * @return EBSJob
     */
    protected function getEBSJobInstance(Job $baseJob): EBSJob
    {
        return new EBSJob(
            $baseJob->getAccount(),
            $baseJob->getDateTime(),
            $baseJob->getRegion(),
            $baseJob->getKey(),
            $baseJob->getSecret()
        );
    }
}
