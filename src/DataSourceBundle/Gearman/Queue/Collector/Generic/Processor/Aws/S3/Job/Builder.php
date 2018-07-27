<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 6/13/17
 * Time: 10:14 AM
 */

namespace DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\S3\Job;

use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\JobCollection;
use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\S3\FilterCollection;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\S3\DataLoader;

/**
 * Class Builder
 * @package DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\S3\Job
 */
class Builder
{

    /**
     * @param DataLoader $loader
     * @param Job $baseJob
     * @param FilterCollection $filters
     * @return JobCollection
     */
    public function build(DataLoader $loader, Job $baseJob, FilterCollection $filters)
    {
        if (!is_null($baseJob->getData())) {
            return $baseJob;
        }

        $jobs = new JobCollection();
        $newFilters = new FilterCollection();
        $buckets = $loader->retrieveBuckets();
        $filter = $filters->at(0);

        foreach ($buckets as $bucket) {
            $job = clone($baseJob);
            $filter->setBucket($bucket);
            $newFilters->add(clone($filter));
            $job->setData(clone($newFilters));
            $jobs->add($job);
            $newFilters->clear();
        }
        return $jobs;
    }
}
