<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 20/03/17
 * Time: 12:07
 */

namespace DataSourceBundle\Tests\Collection\Aws\EC2\Instance;

use DataSourceBundle\Collection\Aws\EC2\Instance\InstanceCollection;
use DataSourceBundle\Collection\Aws\EC2\Instance\InstanceIndexer;
use DataSourceBundle\Entity\Aws\EC2\Instance\Builder;
use PHPUnit\Framework\TestCase;

/**
 * Class InstanceIndexerTest
 * @package DataSourceBundle\Test\Collection\Aws\EC2\Instance
 */
class InstanceIndexerTest extends TestCase
{
    /**
     * @var InstanceIndexer
     */
    protected $indexer;

    public function setUp()
    {
        $this->indexer = new InstanceIndexer();
    }

    /**
     * @param InstanceCollection $instances
     * @dataProvider instanceProvider
     * @test
     */
    public function findIndexes(InstanceCollection $instances)
    {
        $this->indexer->onChanged($instances, $instances->at(0));

        $this->assertEquals(
            [0],
            $this->indexer->findIndexes('platform', 'linux/unix')
        );

        $this->assertEmpty(
            $this->indexer->findIndexes('platform', 'windows')
        );
    }

    public function instanceProvider()
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
                ))
            ]
        ];
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function findANotIndexedProperty()
    {
        $this->indexer->findIndexes('ImageId', 'test');
    }
}
