<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 06/03/17
 * Time: 19:44
 */

namespace DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScaling\Filter;

use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScalingGroup\FilterCollection;
use DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScalingGroup\Filter;

/**
 * Makes measurement groups according to AWS requests dependencies.
 * Class FilterProcessor
 * @package DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScaling
 */
class GroupingResolver
{
    const MEASUREMENTS = [
        [
            'Instances'
        ],
        /*
        [
            'Activities'
        ]
        */
    ];

    /**
     * @return FilterCollection
     */
    protected function getDefaultFilter()
    {
        $newFilters = new FilterCollection();

        foreach (self::MEASUREMENTS as $measurementGroup) {
            $newFilters->add(new Filter('AutoScaling', $measurementGroup));
        }

        return $newFilters;
    }

    /**
     * @param FilterCollection $filters
     * @return FilterCollection
     */
    protected function splitFilter(FilterCollection $filters): FilterCollection
    {
        $newFilters = new FilterCollection();

        foreach ($filters as $filter) {
            /** @var Filter $filter */
            foreach (self::MEASUREMENTS as $group) {
                $result = array_intersect($group, $filter->getMeasurementNames());

                if (empty($result)) {
                    continue;
                }

                $newFilters->add(new Filter(
                    'AutoScaling',
                    $result,
                    $filter->getInstances(),
                    $filter->getAutoScalingGroup()
                ));
            }
        }

        return $newFilters;
    }

    /**
     * @param FilterCollection|null $filters
     * @return FilterCollection
     */
    public function splitMeasurements(
        FilterCollection $filters = null
    ): FilterCollection {
        return (is_null($filters))
            ? $this->getDefaultFilter()
            : $this->splitFilter($filters);
    }
}
