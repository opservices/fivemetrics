<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 17/02/17
 * Time: 10:18
 */

namespace DataSourceBundle\Tests\Entity\Aws\EC2\NetworkInterface;

use DataSourceBundle\Collection\Aws\EC2\PrivateIpAddressCollection;
use DataSourceBundle\Collection\Aws\EC2\SecurityGroupCollection;
use DataSourceBundle\Entity\Aws\EC2\NetworkInterface\Association;
use DataSourceBundle\Entity\Aws\EC2\NetworkInterface\Attachment;
use DataSourceBundle\Entity\Aws\EC2\NetworkInterface\NetworkInterface;
use DataSourceBundle\Entity\Aws\EC2\NetworkInterface\PrivateIpAddress;
use DataSourceBundle\Entity\Aws\EC2\SecurityGroup\SecurityGroup;
use EssentialsBundle\Entity\DateTime\DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Class NetworkInterfaceTest
 * @package Test\Entity\Aws\EC2\NetworkInterface
 */
class NetworkInterfaceTest extends TestCase
{
    /**
     * @var NetworkInterface
     */
    protected $net;

    public function setUp()
    {
        $this->net = new NetworkInterface(
            'networkInterfaceId',
            'subnetId',
            'vpcId',
            'description',
            'ownerId',
            'available',
            'macAddress',
            'privateIpAddress',
            true,
            new SecurityGroupCollection(),
            new Attachment(
                'attachmentId',
                1,
                'attaching',
                DateTime::createFromFormat('Y-m-d H:i', '2017-02-17 08:09'),
                false
            ),
            new PrivateIpAddressCollection(),
            new Association(
                "publicIp",
                "publicDnsName",
                "ipOwnerId"
            ),
            'privateDnsName'
        );
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals(
            'networkInterfaceId',
            $this->net->getNetworkInterfaceId()
        );

        $this->assertEquals(
            'subnetId',
            $this->net->getSubnetId()
        );

        $this->assertEquals(
            'vpcId',
            $this->net->getVpcId()
        );

        $this->assertEquals(
            'description',
            $this->net->getDescription()
        );

        $this->assertEquals(
            'ownerId',
            $this->net->getOwnerId()
        );

        $this->assertEquals(
            'available',
            $this->net->getStatus()
        );

        $this->assertEquals(
            'macAddress',
            $this->net->getMacAddress()
        );

        $this->assertEquals(
            'privateIpAddress',
            $this->net->getPrivateIpAddress()
        );

        $this->assertTrue($this->net->isSourceDestCheck());

        $this->assertInstanceOf(
            'DataSourceBundle\Collection\Aws\EC2\SecurityGroupCollection',
            $this->net->getGroups()
        );

        $this->assertInstanceOf(
            'DataSourceBundle\Entity\Aws\EC2\NetworkInterface\Attachment',
            $this->net->getAttachment()
        );

        $this->assertInstanceOf(
            'DataSourceBundle\Collection\Aws\EC2\PrivateIpAddressCollection',
            $this->net->getPrivateIpAddresses()
        );

        $this->assertInstanceOf(
            'DataSourceBundle\Entity\Aws\EC2\NetworkInterface\Association',
            $this->net->getAssociation()
        );

        $this->assertEquals(
            'privateDnsName',
            $this->net->getPrivateDnsName()
        );
    }

    /**
     * @test
     */
    public function setNetworkInterfaceId()
    {
        $this->net->setNetworkInterfaceId('networkInterfaceId.test');

        $this->assertEquals(
            'networkInterfaceId.test',
            $this->net->getNetworkInterfaceId()
        );
    }

    /**
     * @test
     */
    public function setSubnetId()
    {
        $this->net->setSubnetId('subnetId.test');

        $this->assertEquals(
            'subnetId.test',
            $this->net->getSubnetId()
        );
    }

    /**
     * @test
     */
    public function setVpcId()
    {
        $this->net->setVpcId('vpcId.test');

        $this->assertEquals(
            'vpcId.test',
            $this->net->getVpcId()
        );
    }

    /**
     * @test
     */
    public function setDescription()
    {
        $this->net->setDescription('description.test');

        $this->assertEquals(
            'description.test',
            $this->net->getDescription()
        );
    }

    /**
     * @test
     */
    public function setOwnerId()
    {
        $this->net->setOwnerId('ownerId.test');

        $this->assertEquals(
            'ownerId.test',
            $this->net->getOwnerId()
        );
    }

    /**
     * @test
     */
    public function setStatus()
    {
        $this->net->setStatus('attaching');

        $this->assertEquals(
            'attaching',
            $this->net->getStatus()
        );
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function setInvalidStatus()
    {
        $this->net->setStatus('test');
    }

    /**
     * @test
     */
    public function setMacAddress()
    {
        $this->net->setMacAddress('macAddress.test');

        $this->assertEquals(
            'macAddress.test',
            $this->net->getMacAddress()
        );
    }

    /**
     * @test
     */
    public function setPrivateIpAddress()
    {
        $this->net->setPrivateIpAddress('privateIpAddress.test');

        $this->assertEquals(
            'privateIpAddress.test',
            $this->net->getPrivateIpAddress()
        );
    }

    /**
     * @test
     */
    public function setSourceDestCheck()
    {
        $this->net->setSourceDestCheck(false);
        $this->assertFalse($this->net->isSourceDestCheck());
    }

    /**
     * @test
     */
    public function setSecurityGroups()
    {
        $sgs = new SecurityGroupCollection();
        $sgs->add(
            new SecurityGroup(
                'groupName',
                'groupId'
            )
        );

        $this->net->setSecurityGroups($sgs);

        $this->assertEquals(
            $sgs,
            $this->net->getGroups()
        );
    }

    /**
     * @test
     */
    public function setAttachment()
    {
        $at = new Attachment(
            'attachmentId.test',
            2,
            'detaching',
            DateTime::createFromFormat('Y-m-d H:i', '2017-02-17 10:58'),
            false
        );
        $this->net->setAttachment($at);

        $this->assertEquals(
            $at,
            $this->net->getAttachment()
        );
    }

    /**
     * @test
     */
    public function setPrivateIpAddresses()
    {
        $pIps = new PrivateIpAddressCollection();
        $pIps->add(
            new PrivateIpAddress(
                'privateIpAddress',
                true
            )
        );

        $this->net->setPrivateIpAddresses($pIps);

        $this->assertEquals(
            $pIps,
            $this->net->getPrivateIpAddresses()
        );
    }

    /**
     * @test
     */
    public function setAssociation()
    {
        $association = new Association(
            "publicIp",
            "publicDnsName",
            "ipOwnerId"
        );

        $this->net->setAssociation($association);
        $this->assertEquals(
            $association,
            $this->net->getAssociation()
        );
    }
}
