<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 8/15/17
 * Time: 10:05 AM
 */

namespace DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EBS\Filter;

use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\EBS\FilterCollection;
use DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\EBS\Filter;

/**
 * Class GroupingResolver
 * @package DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EBS\Filter
 */
class GroupingResolver
{

    const MEASUREMENTS = [
        [
            'Volumes',
            'Size',
        ]
    ];

    /**
     * @return FilterCollection
     */
    protected function getDefaultFilter()
    {
        $newFilters = new FilterCollection();

        foreach (self::MEASUREMENTS as $measurementGroup) {
            $newFilters->add(new Filter('EBS', $measurementGroup));
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
            foreach (self::MEASUREMENTS as $group) {
                $result = array_intersect($group, $filter->getMeasurementNames());

                if (empty($result)) {
                    continue;
                }

                $newFilters->add(new Filter('EBS', $result));
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
    public function splitMeasurements(
        FilterCollection $filters = null
    ): FilterCollection {
        return (is_null($filters))
            ? $this->getDefaultFilter()
            : $this->splitFilter($filters);
    }
}
