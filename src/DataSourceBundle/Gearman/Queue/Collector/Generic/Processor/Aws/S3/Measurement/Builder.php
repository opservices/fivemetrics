<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 6/13/17
 * Time: 11:05 AM
 */

namespace DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\S3\Measurement;

use DataSourceBundle\Aws\S3\Measurement\Measurements;
use DataSourceBundle\Collection\Aws\S3\Bucket\BucketCollection;
use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\S3\FilterCollection;
use DataSourceBundle\Entity\Aws\Region\RegionInterface;
use EssentialsBundle\Entity\DateTime\DateTime;

/**
 * Class Builder
 * @package DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\S3\Measurement
 */
class Builder
{
    /**
     * @param RegionInterface $region
     * @param DateTime $dateTime
     * @param BucketCollection $collection
     * @param FilterCollection $filters
     * @return Measurements
     */
    public static function buildMeasurements(
        RegionInterface $region,
        DateTime $dateTime,
        BucketCollection $collection,
        FilterCollection $filters
    ): Measurements {

        $measurements = new Measurements();

        foreach ($filters as $filter) {
            $class = sprintf(
                "DataSourceBundle\\Aws\\S3\\Measurement\\%s\\%s",
                $filter->getNamespace(),
                $filter->getMeasurementNames()[0]
            );

            $measurements->addMeasurement(new $class($region, $dateTime, $collection));
        }
        return $measurements;
    }
}
