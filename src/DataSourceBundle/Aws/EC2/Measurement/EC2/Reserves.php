<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 17/01/17
 * Time: 15:21
 */

namespace DataSourceBundle\Aws\EC2\Measurement\EC2;

use DataSourceBundle\Collection\Aws\EC2\Instance\InstanceCollection;
use DataSourceBundle\Collection\Aws\EC2\Reservation\Instance\ReservationCollection;
use DataSourceBundle\Entity\Aws\EC2\Reservation\Instance\Reservation;
use DataSourceBundle\Entity\Aws\EC2\Reservation\Instance\Reservation as InstanceReservation;
use DataSourceBundle\Entity\Aws\Region\RegionInterface;
use EssentialsBundle\Collection\Metric\MetricCollection;
use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\Entity\Metric\Builder;
use EssentialsBundle\Entity\Metric\RealTimeData;

/**
 * Class Reserves
 * @package DataSourceBundle\Aws\EC2\Measurement\EC2
 */
class Reserves extends MeasurementAbstract
{
    /**
     * @var ReservationCollection
     */
    protected $reservations;

    /**
     * Reservation constructor.
     * @param RegionInterface $region
     * @param DateTime $datetime
     * @param InstanceCollection $instances
     * @param ReservationCollection $reservations
     */
    public function __construct(
        RegionInterface $region,
        DateTime $datetime,
        InstanceCollection $instances,
        ReservationCollection $reservations
    ) {
        parent::__construct($region, $datetime, $instances);
        $this->setReservations($reservations);
    }

    /**
     * @return ReservationCollection
     */
    public function getReservations(): ReservationCollection
    {
        return $this->reservations;
    }

    /**
     * @param ReservationCollection $reservations
     * @return Reserves
     */
    public function setReservations(ReservationCollection $reservations): Reserves
    {
        $this->reservations = $reservations;
        return $this;
    }

    /**
     * @return MetricCollection
     */
    public function getMetrics(): MetricCollection
    {
        $buildData = [];
        $reserves  = $this->getReservations();
        $realTimeData = [];

        /** @var Reservation $reserve */
        foreach ($reserves as $reserve) {
            $az = $reserve->getAvailabilityZone();
            $key = md5($this->getRegion()->getCode() . '-' . $az);

            list($using, $available) = $this->calcUsedReserves($reserve);

            $realTimeData[] = $this->buildRealTimeReserve(
                $reserve,
                $using,
                $available
            );

            if ($reserve->getState() != 'active') {
                continue;
            }

            if (! isset($buildData[$key])) {
                $buildData[$key]['using'] = [
                    'name' => $this->getName([ 'reserves' ]),
                    'tags' => $this->getTags($reserve, $az, 'using'),
                    'points' => [
                        [
                            'value' => 0,
                            'time' => $this->getMetricsDatetime()
                        ]
                    ]
                ];

                $buildData[$key]['available'] = [
                    'name' => $this->getName([ 'reserves' ]),
                    'tags' => $this->getTags($reserve, $az, 'available'),
                    'points' => [
                        [
                            'value' => 0,
                            'time' => $this->getMetricsDatetime()
                        ]
                    ]
                ];
            }

            $buildData[$key]['using']['points'][0]['value']     += $using;
            $buildData[$key]['available']['points'][0]['value'] += $available;
        }

        $metricsData = [];
        foreach ($buildData as $md5 => $data) {
            $metricsData[] = $data['using'];
            $metricsData[] = $data['available'];
        }

        $this->realTimeData = new RealTimeData(
            $this->getName([ 'reserves' ]),
            $realTimeData,
            $this->getRegion()->getCode()
        );

        return Builder::build($metricsData);
    }

    protected function buildRealTimeReserve(
        InstanceReservation $reserve,
        int $using,
        int $available
    ): array {
        return [
            'InstanceType' => $reserve->getInstanceType(),
            'Scope' => $reserve->getScope(),
            'AvailabilityZone' => $reserve->getAvailabilityZone(),
            'ProductDescription' => $reserve->getProductDescription(),
            'RecurringCharges' => $reserve->getRecurringCharges(),
            'State' => $reserve->getState(),
            'Start' => $reserve->getStart(),
            'End' => $reserve->getEnd(),
            'OfferingClass' => $reserve->getOfferingClass(),
            'InstanceCount' => $reserve->getInstanceCount(),
            'InstanceTenancy' => $reserve->getInstanceTenancy(),
            'InstanceUsing' => $using,
            'InstanceAvailable' => $available,
        ];
    }

    /**
     * @param InstanceReservation $reserve
     * @return array
     */
    protected function calcUsedReserves(InstanceReservation $reserve): array
    {
        $foundInstances = $this->getInstances()
            ->matchReservation(
                $reserve,
                'running'
            );

        $available = max(
            $reserve->getInstanceCount() - count($foundInstances),
            0
        );

        $using = $reserve->getInstanceCount() - $available;

        return [$using, $available];
    }

    /**
     * @param InstanceReservation $reserve
     * @param string $az
     * @param string $label
     * @return array
     */
    protected function getTags(
        InstanceReservation $reserve,
        string $az,
        string $label
    ): array {
        $tags = [
            [
                'key' => '::fm::region',
                'value' => $this->getRegion()->getCode()
            ],
            [
                'key' => '::fm::availabilityZone',
                'value' => $az
            ],
            [
                'key' => '::fm::state',
                'value' => $label
            ],
        ];

        $awsTags = array_map(function ($tag) {
            return [
                'key'   => $tag['Key'],
                'value' => $tag['Value'],
            ];
        }, $reserve->getTags()->toArray());

        return array_merge($tags, $awsTags);
    }
}
