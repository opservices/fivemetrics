<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 06/03/17
 * Time: 19:44
 */

namespace DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EC2\Filter;

use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\FilterCollection;
use DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\Filter;

/**
 * Makes measurement groups according to AWS requests dependencies.
 * Class FilterProcessor
 * @package GearmanBundle\Queue\Collector\Generic\Processor\Aws\EC2\Filter
 */
class GroupingResolver
{
    const SERVICE_MEASUREMENTS = [
        'EC2' => [
            [
                'Reserves'
            ],
            [
                'Instances'
            ]
        ],
    ];

    /**
     * @return FilterCollection
     */
    protected static function getDefaultFilter()
    {
        $newFilters = new FilterCollection();

        foreach (self::SERVICE_MEASUREMENTS as $service => $measurementGroups) {
            foreach ($measurementGroups as $measurementGroup) {
                $newFilters->add(new Filter(
                    $service,
                    $measurementGroup
                ));
            }
        }

        return $newFilters;
    }

    /**
     * @param FilterCollection $filters
     * @return FilterCollection
     */
    protected static function splitFilter(FilterCollection $filters): FilterCollection
    {
        $newFilters = new FilterCollection();

        foreach ($filters as $filter) {
            foreach (self::SERVICE_MEASUREMENTS[$filter->getNamespace()] as $group) {
                $result = array_intersect($group, $filter->getMeasurementNames());

                if (empty($result)) {
                    continue;
                }

                $newFilters->add(
                    new Filter($filter->getNamespace(), $result)
                );
            }
        }

        return $newFilters;
    }

    /**
     * @param FilterCollection|null $filters
     * @return FilterCollection
     */
    public static function splitMeasurements(
        FilterCollection $filters = null
    ): FilterCollection {
        return (is_null($filters))
            ? self::getDefaultFilter()
            : self::splitFilter($filters);
    }
}
