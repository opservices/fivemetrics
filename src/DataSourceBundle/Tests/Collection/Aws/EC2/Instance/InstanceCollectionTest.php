<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 20/03/17
 * Time: 14:09
 */

namespace DataSourceBundle\Tests\Collection\Aws\EC2\Instance;

use DataSourceBundle\Collection\Aws\EC2\Instance\InstanceCollection as Collection;
use DataSourceBundle\Collection\Aws\EC2\Instance\InstanceIndexer;
use DataSourceBundle\Collection\Aws\EC2\Reservation\Instance\RecurringChargesCollection;
use DataSourceBundle\Entity\Aws\EC2\Instance\Builder;
use DataSourceBundle\Entity\Aws\EC2\Reservation\Instance\RecurringCharges;
use DataSourceBundle\Entity\Aws\EC2\Reservation\Instance\Reservation;
use EssentialsBundle\Entity\DateTime\DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Class InstanceCollectionTest
 * @package DataSourceBundle\Test\Collection\Aws\EC2\Instance
 */
class InstanceCollectionTest extends TestCase
{
    /**
     * @var Collection
     */
    protected $collection;

    public function setUp()
    {
        $this->collection = new Collection([], new InstanceIndexer());
    }

    /**
     * @param Collection $instances
     * @param Reservation $reservation
     * @test
     * @dataProvider matchedInstanceReservationProvider
     */
    public function matchReservation(Collection $instances, Reservation $reservation)
    {
        foreach ($instances as $instance) {
            $this->collection->add($instance);
        }

        $this->assertEquals(
            $this->collection->at(0),
            $this->collection->matchReservation($reservation, 'running')->at(0)
        );
    }

    public function matchedInstanceReservationProvider()
    {
        return [
            [
                Builder::build(json_decode(
                    '[{
                    "InstanceId": "i-0db441fbe040a2efc",
                    "ImageId": "ami-6d1c2007",
                    "State": {
                        "Code": 16,
                        "Name": "running"
                    },
                    "PrivateDnsName": "ip-172-30-0-36.ec2.internal",
                    "PublicDnsName": "",
                    "StateTransitionReason": "",
                    "KeyName": "opservices-us",
                    "AmiLaunchIndex": 0,
                    "ProductCodes": [
                        {
                            "ProductCodeId": "aw0evgkw8e5c1q413zgy5pjce",
                            "ProductCodeType": "marketplace"
                        }
                    ],
                    "InstanceType": "t2.large",
                    "LaunchTime": "2016-12-19T19:41:19+00:00",
                    "Placement": {
                        "AvailabilityZone": "us-east-1a",
                        "GroupName": "",
                        "Tenancy": "default"
                    },
                    "Monitoring": {
                        "State": "disabled"
                    },
                    "SubnetId": "subnet-d13feda6",
                    "VpcId": "vpc-4f1ea32a",
                    "PrivateIpAddress": "172.30.0.36",
                    "Architecture": "x86_64",
                    "RootDeviceType": "ebs",
                    "RootDeviceName": "/dev/sda1",
                    "BlockDeviceMappings": [
                        {
                            "DeviceName": "/dev/sda1",
                            "Ebs": {
                            "AttachTime": "2016-12-19T19:41:20+00:00",
                                "DeleteOnTermination": false,
                                "VolumeId": "vol-0e6d44e4f391a08b2",
                                "Status": "attached"
                            }
                        },
                        {
                            "DeviceName": "/dev/sdb",
                            "Ebs": {
                            "AttachTime": "2016-12-19T20:02:27+00:00",
                                "DeleteOnTermination": false,
                                "VolumeId": "vol-00d88e00b36757455",
                                "Status": "attached"
                            }
                        }
                    ],
                    "VirtualizationType": "hvm",
                    "ClientToken": "148217644108981449",
                    "Tags": [
                        {
                            "Key": "Name",
                            "Value": "ns3"
                        }
                    ],
                    "SecurityGroups": [
                        {
                            "GroupName": "IPs-OpServices",
                            "GroupId": "sg-06a4947b"
                        }
                    ],
                    "SourceDestCheck": true,
                    "Hypervisor": "xen",
                    "EbsOptimized": false,
                    "NetworkInterfaces": [
                        {
                            "NetworkInterfaceId": "eni-ba6ee155",
                            "SubnetId": "subnet-d13feda6",
                            "VpcId": "vpc-4f1ea32a",
                            "Description": "Primary network interface",
                            "OwnerId": "140020291614",
                            "Status": "in-use",
                            "MacAddress": "0a:35:9b:55:91:44",
                            "PrivateIpAddress": "172.30.0.36",
                            "SourceDestCheck": true,
                            "Groups": [
                                {
                                    "GroupName": "IPs-OpServices",
                                    "GroupId": "sg-06a4947b"
                                }
                            ],
                            "Attachment": {
                            "AttachmentId": "eni-attach-73228bf4",
                                "DeviceIndex": 0,
                                "Status": "attached",
                                "AttachTime": "2016-12-19T19:41:19+00:00",
                                "DeleteOnTermination": true
                            },
                            "PrivateIpAddresses": [
                                {
                                    "PrivateIpAddress": "172.30.0.36",
                                    "Primary": true,
                                    "Association": {
                                    "PublicIp": "34.195.77.200",
                                        "PublicDnsName": "",
                                        "IpOwnerId": "140020291614"
                                    }
                                }
                            ] 
                        }],
                    "EnaSupport": false,
                    "Platform": "Linux/Unix"
                    }]',
                    true
                )),
                new Reservation(
                    'reservedInstancesId',
                    't2.large',
                    DateTime::createFromFormat('Y-m-d H:i', '2017-03-20 14:14'),
                    DateTime::createFromFormat('Y-m-d H:i', '2018-03-20 14:14'),
                    100000,
                    0.1,
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
                    'us-east-1a'
                )
            ]
        ];
    }

    /**
     * @test
     * @expectedException \RuntimeException
     * @dataProvider matchedInstanceReservationProvider
     */
    public function findReservationWithoutInstanceIndexer(
        Collection $instances,
        Reservation $reservation
    ) {
        $instances->matchReservation($reservation, 'running');
    }

    /**
     * @test
     * @dataProvider matchedInstanceReservationProvider
     */
    public function findInstanceId(
        Collection $instances,
        Reservation $reservation
    ) {
        $this->assertEquals(
            $instances->at(0),
            $instances->find('i-0db441fbe040a2efc')
        );
    }

    /**
     * @test
     * @dataProvider matchedInstanceReservationProvider
     */
    public function clear(
        Collection $instances,
        Reservation $reservation
    ) {
        $instances->clear();
        $this->assertEquals(0, count($instances));
        $this->assertNull($instances->find('i-0db441fbe040a2efc'));
    }

    /**
     * @test
     * @dataProvider matchedInstanceReservationProvider
     */
    public function findRemovedInstance(
        Collection $instances,
        Reservation $reservation
    ) {
        foreach ($instances as $instance) {
            $this->collection->add($instance);
        }

        $this->collection->remove(0);
        $this->assertNull(
            $this->collection->find('i-0db441fbe040a2efc')
        );
    }
}
