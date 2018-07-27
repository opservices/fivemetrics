<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 17/02/17
 * Time: 08:47
 */

namespace DataSourceBundle\Tests\Entity\Aws\EC2\NetworkInterface;

use DataSourceBundle\Entity\Aws\EC2\NetworkInterface\Association;
use DataSourceBundle\Entity\Aws\EC2\NetworkInterface\PrivateIpAddress;
use PHPUnit\Framework\TestCase;

/**
 * Class PrivateIpAddressTest
 * @package DataSourceBundle\Tests\Entity\Aws\EC2\NetworkInterface
 */
class PrivateIpAddressTest extends TestCase
{
    /**
     * @var PrivateIpAddress
     */
    protected $privateIpAddress;

    public function setUp()
    {
        $this->privateIpAddress = new PrivateIpAddress(
            'privateIpAddress',
            true,
            null,
            new Association(
                "publicIp",
                "publicDnsName",
                "ipOwnerId"
            )
        );
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals(
            'privateIpAddress',
            $this->privateIpAddress->getPrivateIpAddress()
        );

        $this->assertTrue(
            $this->privateIpAddress->isPrimary()
        );

        $this->assertNull($this->privateIpAddress->getPrivateDnsName());

        $this->assertInstanceOf(
            'DataSourceBundle\Entity\Aws\EC2\NetworkInterface\Association',
            $this->privateIpAddress->getAssociation()
        );
    }

    /**
     * @test
     */
    public function setPrivateIpAddress()
    {
        $this->privateIpAddress->setPrivateIpAddress('privateIpAddress.test');

        $this->assertEquals(
            'privateIpAddress.test',
            $this->privateIpAddress->getPrivateIpAddress()
        );
    }

    /**
     * @test
     */
    public function setPrivateDnsName()
    {
        $this->privateIpAddress->setPrivateDnsName('privateDnsName.test');

        $this->assertEquals(
            'privateDnsName.test',
            $this->privateIpAddress->getPrivateDnsName()
        );
    }

    /**
     * @test
     */
    public function setPrimary()
    {
        $this->privateIpAddress->setPrimary(false);

        $this->assertFalse(
            $this->privateIpAddress->isPrimary()
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

        $this->privateIpAddress->setAssociation($association);

        $this->assertEquals(
            $association,
            $this->privateIpAddress->getAssociation()
        );
    }
}
