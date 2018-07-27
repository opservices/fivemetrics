<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 17/02/17
 * Time: 11:09
 */

namespace DataSourceBundle\Tests\Entity\Aws\EC2\Subnet;

use DataSourceBundle\Collection\Aws\Tag\TagCollection;
use DataSourceBundle\Collection\Aws\EC2\Subnet\Ipv6\Ipv6CidrBlockAssociationCollection;
use DataSourceBundle\Entity\Aws\EC2\Subnet\Ipv6\CidrBlockAssociation;
use DataSourceBundle\Entity\Aws\EC2\Subnet\Ipv6\CidrBlockState;
use DataSourceBundle\Entity\Aws\EC2\Subnet\Subnet;
use DataSourceBundle\Entity\Aws\Tag\Tag;
use PHPUnit\Framework\TestCase;

/**
 * Class SubnetTest
 * @package DataSourceBundle\Tests\Entity\Aws\EC2\Subnet
 */
class SubnetTest extends TestCase
{
    /**
     * @var Subnet
     */
    protected $subnet;

    public function setUp()
    {
        $this->subnet = new Subnet(
            'vpcId',
            'subnetId',
            'pending',
            'availabilityZone',
            10,
            'cidrBlock',
            false,
            false,
            false,
            new TagCollection(),
            new Ipv6CidrBlockAssociationCollection()
        );
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals(
            'vpcId',
            $this->subnet->getVpcId()
        );

        $this->assertEquals(
            'subnetId',
            $this->subnet->getSubnetId()
        );

        $this->assertEquals(
            'pending',
            $this->subnet->getState()
        );

        $this->assertEquals(
            'availabilityZone',
            $this->subnet->getAvailabilityZone()
        );

        $this->assertEquals(
            10,
            $this->subnet->getAvailableIpAddressCount()
        );

        $this->assertEquals(
            'cidrBlock',
            $this->subnet->getCidrBlock()
        );

        $this->assertFalse($this->subnet->isDefaultForAz());

        $this->assertFalse($this->subnet->isMapPublicIpOnLaunch());

        $this->assertFalse($this->subnet->isAssignIpv6AddressOnCreation());

        $this->assertInstanceOf(
            'DataSourceBundle\Collection\Aws\Tag\TagCollection',
            $this->subnet->getTags()
        );

        $this->assertInstanceOf(
            'DataSourceBundle\Collection\Aws\EC2\Subnet\Ipv6\Ipv6CidrBlockAssociationCollection',
            $this->subnet->getIpv6CidrBlockAssociationSet()
        );
    }

    /**
     * @test
     */
    public function setVpcId()
    {
        $this->subnet->setVpcId('vpcId.test');

        $this->assertEquals(
            'vpcId.test',
            $this->subnet->getVpcId()
        );
    }

    /**
     * @test
     */
    public function setSubnetId()
    {
        $this->subnet->setSubnetId('subnetId.test');

        $this->assertEquals(
            'subnetId.test',
            $this->subnet->getSubnetId()
        );
    }

    /**
     * @test
     */
    public function setState()
    {
        $this->subnet->setState('available');

        $this->assertEquals(
            'available',
            $this->subnet->getState()
        );
    }

    /**
     * @test
     */
    public function setAvailabilityZone()
    {
        $this->subnet->setAvailabilityZone('availabilityZone.test');

        $this->assertEquals(
            'availabilityZone.test',
            $this->subnet->getAvailabilityZone()
        );
    }

    /**
     * @test
     */
    public function setAvailableIpAddressCount()
    {
        $this->subnet->setAvailableIpAddressCount(20);

        $this->assertEquals(
            20,
            $this->subnet->getAvailableIpAddressCount()
        );
    }

    /**
     * @test
     */
    public function setCidrBlock()
    {
        $this->subnet->setCidrBlock('cidrBlock.test');

        $this->assertEquals(
            'cidrBlock.test',
            $this->subnet->getCidrBlock()
        );
    }

    /**
     * @test
     */
    public function setDefaultForAz()
    {
        $this->subnet->setDefaultForAz(true);

        $this->assertTrue($this->subnet->isDefaultForAz());
    }

    /**
     * @test
     */
    public function setMapPublicIpOnLaunch()
    {
        $this->subnet->setMapPublicIpOnLaunch(true);
        $this->assertTrue($this->subnet->isMapPublicIpOnLaunch());
    }

    /**
     * @test
     */
    public function setAssignIpv6AddressOnCreation()
    {
        $this->subnet->setAssignIpv6AddressOnCreation(true);

        $this->assertTrue($this->subnet->isAssignIpv6AddressOnCreation());
    }

    /**
     * @test
     */
    public function setTags()
    {
        $tags = new TagCollection();
        $tags->add(
            new Tag('key', 'value')
        );

        $this->subnet->setTags($tags);
        $this->assertEquals(
            $tags,
            $this->subnet->getTags()
        );
    }

    /**
     * @test
     */
    public function setIpv6CidrBlockAssociationSet()
    {
        $ips = new Ipv6CidrBlockAssociationCollection();
        $ips->add(
            new CidrBlockAssociation(
                'test',
                'test',
                new CidrBlockState('test', 'test')
            )
        );

        $this->subnet->setIpv6CidrBlockAssociationSet($ips);
        $this->assertEquals(
            $ips,
            $this->subnet->getIpv6CidrBlockAssociationSet()
        );
    }
}
