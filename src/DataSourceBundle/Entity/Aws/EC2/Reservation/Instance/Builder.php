<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 10/03/17
 * Time: 19:05
 */

namespace DataSourceBundle\Entity\Aws\EC2\Reservation\Instance;

use DataSourceBundle\Collection\Aws\EC2\Reservation\Instance\RecurringChargesCollection;
use DataSourceBundle\Collection\Aws\EC2\Reservation\Instance\ReservationCollection;
use EssentialsBundle\Entity\DateTime\DateTime;
use DataSourceBundle\Entity\Aws\Tag\Builder as TagsBuilder;

/**
 * Class Builder
 * @package DataSourceBundle\Entity\Aws\EC2\Reservation\Instance
 */
class Builder
{
    /**
     * @param array $data
     * @return ReservationCollection
     */
    public static function build(array $data): ReservationCollection
    {
        $reservations = new ReservationCollection();

        foreach ($data as $reservation) {
            $reservations->add(
                new Reservation(
                    $reservation['ReservedInstancesId'],
                    $reservation['InstanceType'],
                    new DateTime($reservation['Start']),
                    new DateTime($reservation['End']),
                    $reservation['Duration'],
                    $reservation['UsagePrice'],
                    $reservation['FixedPrice'],
                    $reservation['InstanceCount'],
                    $reservation['ProductDescription'],
                    $reservation['State'],
                    $reservation['InstanceTenancy'],
                    $reservation['CurrencyCode'],
                    $reservation['OfferingType'],
                    self::buildRecurringCharges($reservation['RecurringCharges']),
                    $reservation['OfferingClass'],
                    $reservation['Scope'],
                    (empty($reservation['AvailabilityZone']))
                        ? null
                        : $reservation['AvailabilityZone'],
                    TagsBuilder::build($reservation['Tags'])
                )
            );
        }

        return $reservations;
    }

    protected static function buildRecurringCharges(array $data): RecurringChargesCollection
    {
        $recurringCharges = new RecurringChargesCollection();

        foreach ($data as $recurringCharge) {
            $recurringCharges->add(new RecurringCharges(
                $recurringCharge['Amount'],
                $recurringCharge['Frequency']
            ));
        }

        return $recurringCharges;
    }
}
