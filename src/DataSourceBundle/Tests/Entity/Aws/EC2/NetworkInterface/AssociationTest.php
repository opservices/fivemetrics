<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 16/02/17
 * Time: 18:10
 */

namespace DataSourceBundle\Tests\Entity\Aws\EC2\NetworkInterface;

use DataSourceBundle\Entity\Aws\EC2\NetworkInterface\Association;
use PHPUnit\Framework\TestCase;

/**
 * Class AssociationTest
 * @package Test\Entity\Aws\EC2\NetworkInterface
 */
class AssociationTest extends TestCase
{
    /**
     * @var Association
     */
    protected $association;

    public function setUp()
    {
        $this->association = new Association(
            "publicIp",
            "publicDnsName",
            "ipOwnerId"
        );
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals(
            "publicIp",
            $this->association->getPublicIp()
        );

        $this->assertEquals(
            "publicDnsName",
            $this->association->getPublicDnsName()
        );

        $this->assertEquals(
            "ipOwnerId",
            $this->association->getIpOwnerId()
        );
    }

    /**
     * @test
     */
    public function setPublicIp()
    {
        $this->association->setPublicIp("publicIp.test");

        $this->assertEquals(
            "publicIp.test",
            $this->association->getPublicIp()
        );
    }

    /**
     * @test
     */
    public function setPublicDnsName()
    {
        $this->association->setPublicDnsName("publicDnsName.test");

        $this->assertEquals(
            "publicDnsName.test",
            $this->association->getPublicDnsName()
        );
    }

    /**
     * @test
     */
    public function setIpOwnerId()
    {
        $this->association->setIpOwnerId("ipOwnerId.test");

        $this->assertEquals(
            "ipOwnerId.test",
            $this->association->getIpOwnerId()
        );
    }
}
