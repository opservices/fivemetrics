<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 06/03/17
 * Time: 14:53
 */

namespace DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer\Job;

use DataSourceBundle\Collection\Aws\ElasticLoadBalancer\ElasticLoadBalancerCollection;
use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer\FilterCollection;
use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\JobCollection;
use DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer\Filter;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer\DataLoader;

/**
 * Class Builder
 * @package DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer\Job
 */
class Builder
{
    /**
     * @param DataLoader $client
     * @param Job $baseJob
     * @param FilterCollection $filters
     * @return JobCollection
     */
    public function build(
        DataLoader $client,
        Job $baseJob,
        FilterCollection $filters
    ) {
        $jobs = new JobCollection();

        if (count($filters) == 0) {
            return $jobs;
        }

        if ((count($filters) == 1) && (! $this->isNeedModifyFilter($filters->at(0)))) {
            $jobs->add($baseJob);
            return $jobs;
        }

        $newFilters = new FilterCollection();
        $newElbs = new ElasticLoadBalancerCollection();

        $elbs = $client->retrieveElasticLoadBalancers();

        foreach ($filters as $filter) {
            foreach ($elbs as $elb) {
                $newElbs->add($elb);

                $job = clone($baseJob);

                $newFilters->add(new Filter(
                    $filter->getNamespace(),
                    $filter->getMeasurementNames(),
                    clone $newElbs
                ));

                $job->setData(clone($newFilters));
                $jobs->add($job);

                $newElbs->clear();
                $newFilters->clear();
            }
        }

        return $jobs;
    }

    /**
     * @param Filter $filter
     * @return bool
     */
    protected function isNeedModifyFilter(Filter $filter): bool
    {
        return (
            (count($filter->getMeasurementNames()) == 1)
            && ($filter->getMeasurementNames()[0] == 'Instances')
            && (is_null($filter->getElbs()))
        );
    }
}
