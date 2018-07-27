<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 13/02/17
 * Time: 15:11
 */

namespace DataSourceBundle\Entity\Aws\EC2\Subnet;

use DataSourceBundle\Collection\Aws\EC2\Subnet\Ipv6\Ipv6CidrBlockAssociationCollection;
use DataSourceBundle\Collection\Aws\EC2\Subnet\SubnetCollection;
use DataSourceBundle\Entity\Aws\EC2\Subnet\Ipv6\CidrBlockAssociation;
use DataSourceBundle\Entity\Aws\EC2\Subnet\Ipv6\CidrBlockState;
use DataSourceBundle\Entity\Aws\Tag\Builder as TagsBuilder;

/**
 * Class Builder
 * @package DataSourceBundle\Entity\Aws\EC2\Subnet
 */
class Builder
{
    /**
     * @param array $data
     * @return SubnetCollection
     */
    public static function build(array $data): SubnetCollection
    {
        $subnets = new SubnetCollection();

        foreach ($data as $subnet) {
            $subnets->add(
                new Subnet(
                    $subnet['VpcId'],
                    $subnet['SubnetId'],
                    $subnet['State'],
                    $subnet['AvailabilityZone'],
                    $subnet['AvailableIpAddressCount'],
                    $subnet['CidrBlock'],
                    $subnet['DefaultForAz'],
                    $subnet['MapPublicIpOnLaunch'],
                    $subnet['AssignIpv6AddressOnCreation'],
                    (is_null($subnet['Tags']))
                        ? null
                        : TagsBuilder::build($subnet['Tags']),
                    (is_null($subnet['Ipv6CidrBlockAssociationSet']))
                        ? null
                        : self::buildIpv6CidrBlockAssociationSet($subnet['Ipv6CidrBlockAssociationSet'])
                )
            );
        }

        return $subnets;
    }

    /**
     * @param array $data
     * @return Ipv6CidrBlockAssociationCollection
     */
    protected static function buildIpv6CidrBlockAssociationSet(
        array $data
    ): Ipv6CidrBlockAssociationCollection {
        $ipv6CidrBlockAssociationSet = new Ipv6CidrBlockAssociationCollection();

        foreach ($data as $association) {
            $ipv6CidrBlockAssociationSet->add(
                new CidrBlockAssociation(
                    $association['AssociationId'],
                    $association['Ipv6CidrBlock'],
                    self::buildCidrBlockState($association['Ipv6CidrBlockState'])
                )
            );
        }

        return $ipv6CidrBlockAssociationSet;
    }

    /**
     * @param array $data
     * @return CidrBlockState
     */
    protected static function buildCidrBlockState(array $data): CidrBlockState
    {
        return new CidrBlockState(
            $data['State'],
            $data['StatusMessage']
        );
    }
}
