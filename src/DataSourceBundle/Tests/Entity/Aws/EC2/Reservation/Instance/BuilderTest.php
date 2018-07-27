<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 17/03/17
 * Time: 09:31
 */

namespace DataSourceBundle\Tests\Entity\Aws\EC2\Reservation\Instance;

use DataSourceBundle\Collection\Aws\Tag\TagCollection;
use DataSourceBundle\Collection\Aws\EC2\Reservation\Instance\RecurringChargesCollection;
use DataSourceBundle\Collection\Aws\EC2\Reservation\Instance\ReservationCollection;
use DataSourceBundle\Entity\Aws\Tag\Tag;
use DataSourceBundle\Entity\Aws\EC2\Reservation\Instance\Builder;
use DataSourceBundle\Entity\Aws\EC2\Reservation\Instance\RecurringCharges;
use DataSourceBundle\Entity\Aws\EC2\Reservation\Instance\Reservation;
use EssentialsBundle\Entity\DateTime\DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Class BuilderTest
 * @package DataSourceBundle\Tests\Entity\Aws\EC2\Reservation\Instance
 */
class BuilderTest extends TestCase
{

    /**
     * @test
     * @dataProvider validReservationDataProvider
     */
    public function buildReservationCollection($data, $reserves)
    {
        $this->assertEquals($reserves, Builder::build($data));
    }

    public function validReservationDataProvider()
    {
        return [
            [
                json_decode(
                    '[{
                        "AvailabilityZone": "",
                        "CurrencyCode": "USD",
                        "Duration": 31536000,
                        "End": "2018-03-17T09:05:00+00:00",
                        "FixedPrice": 0,
                        "InstanceCount": 1,
                        "InstanceTenancy": "default",
                        "InstanceType": "c4.large",
                        "OfferingClass": "standard",
                        "OfferingType": "Partial Upfront",
                        "ProductDescription": "Linux/UNIX",
                        "RecurringCharges": [
                            {
                                "Amount": 0.01,
                                "Frequency": "Hourly"
                            }
                        ],
                        "ReservedInstancesId": "test",
                        "Scope": "Region",
                        "Start": "2017-03-17T09:05:00+00:00",
                        "State": "active",
                        "Tags": [
                            {
                                "Key": "unit",
                                "Value": "test"
                            }
                        ],
                        "UsagePrice": 0.01
                    }]',
                    true
                ),
                new ReservationCollection([ new Reservation(
                    'test',
                    'c4.large',
                    DateTime::createFromFormat('Y-m-d H:i', '2017-03-17 09:05'),
                    DateTime::createFromFormat('Y-m-d H:i', '2018-03-17 09:05'),
                    31536000,
                    0.01,
                    0,
                    1,
                    'Linux/UNIX',
                    'active',
                    'default',
                    'USD',
                    'Partial Upfront',
                    new RecurringChargesCollection([ new RecurringCharges(0.01, 'Hourly') ]),
                    'standard',
                    'Region',
                    "",
                    new TagCollection([ new Tag('unit', 'test') ])
                )])
            ],
            [
                json_decode(
                    '[{
                        "AvailabilityZone": "us-east-1a",
                        "CurrencyCode": "USD",
                        "Duration": 31536000,
                        "End": "2018-03-17T09:05:00+00:00",
                        "FixedPrice": 0,
                        "InstanceCount": 1,
                        "InstanceTenancy": "default",
                        "InstanceType": "c4.large",
                        "OfferingClass": "standard",
                        "OfferingType": "Partial Upfront",
                        "ProductDescription": "Linux/UNIX",
                        "RecurringCharges": [
                            {
                                "Amount": 0.01,
                                "Frequency": "Hourly"
                            }
                        ],
                        "ReservedInstancesId": "test",
                        "Scope": "Availability Zone",
                        "Start": "2017-03-17T09:05:00+00:00",
                        "State": "active",
                        "Tags": [
                            {
                                "Key": "unit",
                                "Value": "test"
                            }
                        ],
                        "UsagePrice": 0.01
                    }]',
                    true
                ),
                new ReservationCollection([ new Reservation(
                    'test',
                    'c4.large',
                    DateTime::createFromFormat('Y-m-d H:i', '2017-03-17 09:05'),
                    DateTime::createFromFormat('Y-m-d H:i', '2018-03-17 09:05'),
                    31536000,
                    0.01,
                    0,
                    1,
                    'Linux/UNIX',
                    'active',
                    'default',
                    'USD',
                    'Partial Upfront',
                    new RecurringChargesCollection([ new RecurringCharges(0.01, 'Hourly') ]),
                    'standard',
                    'Availability Zone',
                    "us-east-1a",
                    new TagCollection([ new Tag('unit', 'test') ])
                )])
            ]
        ];
    }
}
