<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 8/16/17
 * Time: 10:22 AM
 */

namespace DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EBS\Measurement;

use DataSourceBundle\Aws\EC2\Measurement\Measurements;
use DataSourceBundle\Collection\Aws\EBS\Volume\VolumeCollection;
use DataSourceBundle\Entity\Aws\Region\RegionInterface;
use DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\EBS\Filter;
use EssentialsBundle\Entity\DateTime\DateTime;

/**
 * Class Builder
 * @package DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EBS\Measurement
 */
class Builder
{
    public static function buildMeasurements(
        RegionInterface $region,
        DateTime $dateTime,
        VolumeCollection $collection,
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

            $measurements->addMeasurement(new $class($region, $dateTime, $collection));
        }
        return $measurements;
    }
}
