<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 06/03/17
 * Time: 19:44
 */

namespace DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer\Filter;

use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer\FilterCollection;
use DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer\Filter;

/**
 * Makes measurement groups according to AWS requests dependencies.
 * Class FilterProcessor
 * @package DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer
 */
class GroupingResolver
{
    const MEASUREMENTS = [
        [
            'Instances'
        ]
    ];

    /**
     * @return FilterCollection
     */
    protected function getDefaultFilter()
    {
        $newFilters = new FilterCollection();

        foreach (self::MEASUREMENTS as $measurementGroup) {
            $newFilters->add(new Filter('ElasticLoadBalancer', $measurementGroup));
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

                $newFilters->add(new Filter('ElasticLoadBalancer', $result));
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
