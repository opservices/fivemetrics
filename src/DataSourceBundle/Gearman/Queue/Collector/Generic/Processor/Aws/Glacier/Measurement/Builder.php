<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 7/28/17
 * Time: 1:57 PM
 */

namespace DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier\Measurement;

use DataSourceBundle\Aws\Glacier\Measurement\Measurements;
use DataSourceBundle\Entity\Aws\Region\RegionInterface;
use DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier\Filter;
use EssentialsBundle\Collection\TypedCollectionAbstract;
use EssentialsBundle\Entity\DateTime\DateTime;

/**
 * Class Builder
 * @package DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier\Measurement
 */
class Builder
{
    /**
     * @param RegionInterface $region
     * @param DateTime $dateTime
     * @param TypedCollectionAbstract $collection
     * @param Filter $filter
     * @return Measurements
     */
    public static function buildMeasurements(
        RegionInterface $region,
        DateTime $dateTime,
        TypedCollectionAbstract $collection,
        Filter $filter
    ): Measurements {
        $measurements = new Measurements();
        $measurementNames = $filter->getMeasurementNames();

        foreach ($measurementNames as $measurement) {
            $class = sprintf(
                "DataSourceBundle\\Aws\\Glacier\\Measurement\\%s\\%s",
                $filter->getNamespace(),
                $measurement
            );

            $measurements->addMeasurement(new $class($region, $dateTime, $collection));
        }

        return $measurements;
    }
}
