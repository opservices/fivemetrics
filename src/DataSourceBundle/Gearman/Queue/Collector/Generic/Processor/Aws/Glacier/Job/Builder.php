<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 7/27/17
 * Time: 2:10 PM
 */

namespace DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier\Job;

use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier\FilterCollection;
use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\JobCollection;
use DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier\Filter;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier\DataLoader;

/**
 * Class Builder
 * @package DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier\Job
 */
class Builder
{
    /**
     * @param DataLoader $client
     * @param Job $baseJob
     * @param FilterCollection $filters
     * @return JobCollection|Job
     */
    public function build(
        DataLoader $client,
        Job $baseJob,
        FilterCollection $filters
    ) {
        if (! is_null($baseJob->getData())) {
            return $baseJob;
        }

        $jobs = new JobCollection();
        $newFilters = new FilterCollection();
        $vaults = $client->retrieveVaults();

        foreach ($filters as $filter) {
            if (count($filter->getMeasurementNames()) > 1) {
                for ($i = 0; $i < count($filter->getMeasurementNames()); $i++) {
                    $job = clone($baseJob);

                    $newFilters->add(new Filter(
                        $filter->getNamespace() . "\\Vault",
                        [$filter->getMeasurementNames()[$i]]
                    ));

                    $job->setData(clone($newFilters));
                    $jobs->add($job);

                    $newFilters->clear();
                }
                continue;
            }

            foreach ($vaults as $vault) {
                $job = clone($baseJob);

                $newFilters->add(new Filter(
                    $filter->getNamespace() . "\\Job",
                    $filter->getMeasurementNames(),
                    clone($vault)
                ));

                $job->setData(clone($newFilters));
                $jobs->add($job);

                $newFilters->clear();
            }
        }
        return $jobs;
    }
}
