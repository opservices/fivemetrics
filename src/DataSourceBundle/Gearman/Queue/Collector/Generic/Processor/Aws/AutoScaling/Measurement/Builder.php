<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 06/03/17
 * Time: 19:59
 */

namespace DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScaling\Measurement;

use DataSourceBundle\Collection\Aws\AutoScaling\AutoScalingGroupCollection;
use DataSourceBundle\Aws\EC2\Measurement\Measurements;
use DataSourceBundle\Entity\Aws\Region\RegionInterface;
use DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\Filter;
use EssentialsBundle\Entity\DateTime\DateTime;

/**
 * Class Builder
 * @package DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScaling\Measurement
 */
class Builder
{
    /**
     * @param RegionInterface $region
     * @param DateTime $dateTime
     * @param AutoScalingGroupCollection $collection
     * @param Filter $filter
     * @return Measurements
     */
    public static function buildMeasurements(
        RegionInterface $region,
        DateTime $dateTime,
        AutoScalingGroupCollection $collection,
        Filter $filter
    ): Measurements {
        $measurements = new Measurements();
        $measurementNames = $filter->getMeasurementNames();

        foreach ($measurementNames as $measurement) {
            $class = sprintf(
                "DataSourceBundle\\Aws\\EC2\\Measurement\\%s\\%s",
                $filter->getNamespace(),
                $measurement
            );

            $measurements->addMeasurement(
                new $class($region, $dateTime, $collection)
            );
        }

        return $measurements;
    }
}
