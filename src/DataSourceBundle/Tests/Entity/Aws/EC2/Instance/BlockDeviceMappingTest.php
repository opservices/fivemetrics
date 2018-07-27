<?php

namespace DataSourceBundle\Tests\Entity\Aws\EC2\Instance;

use DataSourceBundle\Entity\Aws\EC2\Instance\BlockDeviceMapping;
use DataSourceBundle\Entity\Aws\EC2\Instance\Ebs;
use EssentialsBundle\Entity\DateTime\DateTime;
use PHPUnit\Framework\TestCase;

class BlockDeviceMappingTest extends TestCase
{
    /**
     * @var BlockDeviceMapping
     */
    protected $instance;
    /**
     * @var Ebs
     */
    protected $ebs;

    public function setUp()
    {
        $this->ebs = new Ebs(new DateTime(), true, 'id', 'attached');
        $this->instance = new BlockDeviceMapping('name', $this->ebs);
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals(
            "name",
            $this->instance->getDeviceName()
        );
        $this->assertEquals(
            $this->ebs,
            $this->instance->getEbs()
        );
    }

    /**
     * @test
     */
    public function trySetDeviceName()
    {
        $this->instance->setDeviceName("name");

        $this->assertEquals(
            "name",
            $this->instance->getDeviceName()
        );
    }

    /**
     * @test
     */
    public function trySetEbs()
    {

        $this->instance->setEbs($this->ebs);

        $this->assertEquals(
            $this->ebs,
            $this->instance->getEbs()
        );
    }

}