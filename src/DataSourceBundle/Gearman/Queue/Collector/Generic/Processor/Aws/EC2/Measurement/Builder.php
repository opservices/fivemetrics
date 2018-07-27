<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 06/03/17
 * Time: 19:59
 */

namespace DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EC2\Measurement;

use DataSourceBundle\Collection\Aws\EC2\Reservation\Instance\ReservationCollection;
use EssentialsBundle\Collection\TypedCollectionAbstract;
use DataSourceBundle\Aws\EC2\Measurement\Measurements;
use DataSourceBundle\Entity\Aws\Region\RegionInterface;
use DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\Filter;
use EssentialsBundle\Entity\DateTime\DateTime;

/**
 * Class Builder
 * @package DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EC2\Measurement
 */
class Builder
{
    /**
     * @param RegionInterface $region
     * @param DateTime $datetime
     * @param TypedCollectionAbstract $collection
     * @param Filter $filter
     * @param ReservationCollection|null $reserves
     * @return Measurements
     */
    public static function buildMeasurements(
        RegionInterface $region,
        DateTime $datetime,
        TypedCollectionAbstract $collection,
        Filter $filter,
        ReservationCollection $reserves = null
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
                ($measurement == 'Reserves')
                    ? new $class($region, $datetime, $collection, $reserves)
                    : new $class($region, $datetime, $collection)
            );
        }

        return $measurements;
    }
}
