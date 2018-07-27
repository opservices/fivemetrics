<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 17/02/17
 * Time: 13:14
 */

namespace DataSourceBundle\Tests\Entity\Aws\EC2\Subnet;

use DataSourceBundle\Entity\Aws\EC2\Subnet\Builder;
use PHPUnit\Framework\TestCase;

/**
 * Class BuilderTest
 * @package DataSourceBundle\Tests\Entity\Aws\EC2\Subnet
 */
class BuilderTest extends TestCase
{
    /**
     * @test
     * @dataProvider validSubnetData
     * @param $data
     */
    public function buildValidSubnet($data)
    {
        $subnets = Builder::build([$data]);

        $this->assertInstanceOf(
            'DataSourceBundle\Collection\Aws\EC2\Subnet\SubnetCollection',
            $subnets
        );

        $this->assertEquals(
            json_encode($data),
            json_encode($subnets->current())
        );
    }

    public function validSubnetData()
    {
        $subnets = [
            '{
                "AssignIpv6AddressOnCreation": true,
                "AvailabilityZone": "AvailabilityZone",
                "AvailableIpAddressCount": 10,
                "CidrBlock": "CidrBlock",
                "DefaultForAz": true,
                "Ipv6CidrBlockAssociationSet": [
                    {
                        "AssociationId": "AssociationId",
                        "Ipv6CidrBlock": "Ipv6CidrBlock",
                        "Ipv6CidrBlockState": {
                            "State": "State",
                            "StatusMessage": "StatusMessage"
                        }
                    }
                ],
                "MapPublicIpOnLaunch": true,
                "State": "State",
                "SubnetId": "SubnetId",
                "Tags": [
                    {
                        "Key": "Key",
                        "Value": "Value"
                    }
                ],
                "VpcId": "VpcId"
            }',
            '{
                "AssignIpv6AddressOnCreation": true,
                "AvailabilityZone": "AvailabilityZone",
                "AvailableIpAddressCount": 10,
                "CidrBlock": "CidrBlock",
                "DefaultForAz": true,
                "Ipv6CidrBlockAssociationSet": [],
                "MapPublicIpOnLaunch": true,
                "State": "State",
                "SubnetId": "SubnetId",
                "Tags":[],
                "VpcId": "VpcId"
            }'
        ];

        foreach ($subnets as $subnet) {
            yield [ json_decode($subnet, true) ];
        }
    }
}
