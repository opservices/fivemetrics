<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 7/27/17
 * Time: 2:07 PM
 */

namespace DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier\Filter;

use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier\FilterCollection;
use DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier\Filter;

/**
 * Class GroupingResolver
 * @package DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier\Filter
 */
class GroupingResolver
{
    const MEASUREMENTS = [
        [
            'VaultArchive',
            'VaultSize'
        ],
        [
            'Job'
        ]
    ];

    /**
     * @return FilterCollection
     */
    protected function getDefaultFilter()
    {
        $newFilters = new FilterCollection();

        foreach (self::MEASUREMENTS as $measurementGroup) {
            $newFilters->add(new Filter('Glacier', $measurementGroup));
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
                $newFilters->add(new Filter('Glacier', $result));
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
