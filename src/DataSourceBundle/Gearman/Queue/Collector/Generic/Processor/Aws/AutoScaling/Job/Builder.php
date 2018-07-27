<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 06/03/17
 * Time: 14:53
 */

namespace DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScaling\Job;

use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScalingGroup\FilterCollection;
use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\JobCollection;
use DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScalingGroup\Filter;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScaling\DataLoader;

/**
 * Class Builder
 * @package DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScaling\Job
 */
class Builder
{
    /**
     * @param DataLoader $loader
     * @param Job $baseJob
     * @param FilterCollection $filters
     * @return JobCollection
     */
    public function build(
        DataLoader $loader,
        Job $baseJob,
        FilterCollection $filters
    ): JobCollection {
        $jobs = new JobCollection();

        if ($filters->count() == 1) {
            if (! $this->isActivitiesFilter($filters->at(0))) {
                $jobs->add($baseJob);
                return $jobs;
            }

            return $this->buildActivityJobs($loader, $baseJob, $filters->at(0));
        }

        $newFilters = new FilterCollection();

        foreach ($filters as $filter) {
            $job = clone($baseJob);

            $newFilters->add(clone($filter));
            $job->setData(clone($newFilters));

            $jobs->add($job);

            $newFilters->clear();
        }

        return $jobs;
    }

    /**
     * @param Filter $filter
     * @return bool
     */
    public function isActivitiesFilter(Filter $filter): bool
    {
        return (
            (count($filter->getMeasurementNames()) == 1)
            && ($filter->getMeasurementNames()[0] == 'Activities')
            && (is_null($filter->getAutoScalingGroup()))
        );
    }

    /**
     * @param DataLoader $loader
     * @param Job $baseJob
     * @param Filter $filter
     * @return JobCollection
     */
    protected function buildActivityJobs(
        DataLoader $loader,
        Job $baseJob,
        Filter $filter
    ): JobCollection {
        $jobs = new JobCollection();
        $newFilters = new FilterCollection();

        $groups = $loader->retrieveAutoScalingGroups();

        foreach ($groups as $group) {
            $job = clone($baseJob);
            $filter->setAutoScalingGroup($group);
            $newFilters->add(clone($filter));
            $job->setData(clone($newFilters));

            $jobs->add($job);

            $newFilters->clear();
        }

        return $jobs;
    }
}
