<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 15/02/17
 * Time: 17:39
 */

namespace DataSourceBundle\Tests\Entity\Aws\ElasticLoadBalancer;

use DataSourceBundle\Entity\Aws\ElasticLoadBalancer\InstanceHealth;
use PHPUnit\Framework\TestCase;

/**
 * Class InstanceHealthTest
 * @package DataSourceBundle\Tests\Entity\Aws\ElasticLoadBalancer
 */
class InstanceHealthTest extends TestCase
{
    /**
     * @var InstanceHealth
     */
    protected $instanceHealth;

    public function setUp()
    {
        $this->instanceHealth = new InstanceHealth(
            "id",
            "test",
            "code",
            "unit test"
        );
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals(
            "id",
            $this->instanceHealth->getInstanceId()
        );

        $this->assertEquals(
            "test",
            $this->instanceHealth->getState()
        );

        $this->assertEquals(
            "code",
            $this->instanceHealth->getReasonCode()
        );

        $this->assertEquals(
            "unit test",
            $this->instanceHealth->getDescription()
        );
    }

    /**
     * @test
     */
    public function changeValues()
    {
        $this->instanceHealth->setInstanceId("b")
            ->setState("stopped")
            ->setReasonCode("test code")
            ->setDescription("test");

        $this->assertEquals(
            "b",
            $this->instanceHealth->getInstanceId()
        );

        $this->assertEquals(
            "stopped",
            $this->instanceHealth->getState()
        );

        $this->assertEquals(
            "test code",
            $this->instanceHealth->getReasonCode()
        );

        $this->assertEquals(
            "test",
            $this->instanceHealth->getDescription()
        );
    }
}
