<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 6/13/17
 * Time: 10:42 AM
 */

namespace DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\S3\Filter;

use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\S3\FilterCollection;
use DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\S3\Filter;

/**
 * Class GroupingResolver
 * @package DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\S3\Filter
 */
class GroupingResolver
{
    const MEASUREMENTS = [
        [
            'Versioning'
        ]
    ];

    /**
     * @return FilterCollection
     */
    protected static function getDefaultFilter()
    {
        $newFilters = new FilterCollection();

        foreach (self::MEASUREMENTS as $measurement) {
            $newFilters->add(new Filter('S3', $measurement));
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
            foreach (self::MEASUREMENTS as $group) {
                $result = array_intersect($group, $filter->getMeasurementNames());

                if (empty($result)) {
                    continue;
                }
                $newFilters->add(new Filter('S3', $result));
            }
        }

        return (count($newFilters) == count($filters))
            ? $filters
            : $newFilters;
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
