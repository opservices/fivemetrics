<?php

namespace DataSourceBundle\Tests\Entity\Aws\EC2\Subnet\Ipv6;

use DataSourceBundle\Entity\Aws\EC2\Subnet\Ipv6\CidrBlockState;
use PHPUnit\Framework\TestCase;

class CidrBlockStateTest extends TestCase
{
    /**
     * @var CidrBlockState
     */
    protected $cirBlockState;

    public function setUp()
    {
        $this->cirBlockState = new CidrBlockState("test", "test");
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals(
            "test",
            $this->cirBlockState->getState()
        );
        $this->assertEquals(
            "test",
            $this->cirBlockState->getStatusMessage()
        );
    }

    /**
     * @test
     */
    public function tryDefineState()
    {
        $this->cirBlockState->setState("stateName");

        $this->assertEquals(
            "stateName",
            $this->cirBlockState->getState()
        );
    }

    /**
     * @test
     */
    public function tryDefineStatusMessage()
    {
        $this->cirBlockState->setStatusMessage("StatusMessage");

        $this->assertEquals(
            "StatusMessage",
            $this->cirBlockState->getStatusMessage()
        );
    }

}