<?php

namespace Test\Entity\Aws\EC2\Subnet\Ipv6;

use DataSourceBundle\Entity\Aws\EC2\Subnet\Ipv6\CidrBlockAssociation;
use DataSourceBundle\Entity\Aws\EC2\Subnet\Ipv6\CidrBlockState;
use PHPUnit\Framework\TestCase;

class CidrBlockAssociationTest extends TestCase
{
    /**
     * @var CidrBlockAssociation
     */
    protected $cidrBlockAssociation;

    public function setUp()
    {
        $this->cidrBlockAssociation = new CidrBlockAssociation(
            'test',
            'test',
            new CidrBlockState('test', 'test')
        );

    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals(
            "test",
            $this->cidrBlockAssociation->getAssociationId()
        );
        $this->assertEquals(
            "test",
            $this->cidrBlockAssociation->getIpv6CidrBlock()
        );
        $this->assertEquals(
            new CidrBlockState('test', 'test'),
            $this->cidrBlockAssociation->getIpv6CidrBlockState()
        );

    }

    /**
     * @test
     */
    public function trySetAssociationId()
    {
        $this->cidrBlockAssociation->setAssociationId("id");

        $this->assertEquals(
            "id",
            $this->cidrBlockAssociation->getAssociationId()
        );
    }

    /**
     * @test
     */
    public function trySetIpv6CidrBlock()
    {
        $this->cidrBlockAssociation->setIpv6CidrBlock("id");

        $this->assertEquals(
            "id",
            $this->cidrBlockAssociation->getIpv6CidrBlock()
        );
    }

    /**
     * @test
     */
    public function trySetIpv6CidrBlockState()
    {
        $blockState = new CidrBlockState('testeblock', 'testeblock');
        $this->cidrBlockAssociation->setIpv6CidrBlockState($blockState);

        $this->assertEquals(
            $blockState,
            $this->cidrBlockAssociation->getIpv6CidrBlockState()
        );
    }

}