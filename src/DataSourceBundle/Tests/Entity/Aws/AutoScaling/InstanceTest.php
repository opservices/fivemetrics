<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 16/02/17
 * Time: 09:23
 */

namespace DataSourceBundle\Tests\Entity\Aws\AutoScaling;

use DataSourceBundle\Entity\Aws\AutoScaling\Instance;
use PHPUnit\Framework\TestCase;

/**
 * Class InstanceTest
 * @package Test\Entity\Aws\AutoScaling
 */
class InstanceTest extends TestCase
{
    /**
     * @var Instance
     */
    protected $instance;

    public function setUp()
    {
        $this->instance = new Instance(
            "instanceId",
            "az1",
            "Pending",
            "healthStatus",
            "launchConfigurationName",
            false,
            "autoScalingGroupName"
        );
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals(
            "instanceId",
            $this->instance->getInstanceId()
        );

        $this->assertEquals(
            "az1",
            $this->instance->getAvailabilityZone()
        );

        $this->assertEquals(
            "Pending",
            $this->instance->getLifecycleState()
        );

        $this->assertEquals(
            "healthStatus",
            $this->instance->getHealthStatus()
        );

        $this->assertEquals(
            "launchConfigurationName",
            $this->instance->getLaunchConfigurationName()
        );

        $this->assertFalse($this->instance->isProtectedFromScaleIn());

        $this->assertEquals(
            "autoScalingGroupName",
            $this->instance->getAutoScalingGroupName()
        );
    }

    /**
     * @test
     */
    public function setInstanceId()
    {
        $this->instance->setInstanceId("instanceId.test");

        $this->assertEquals(
            "instanceId.test",
            $this->instance->getInstanceId()
        );
    }

    /**
     * @test
     */
    public function setAvailabilityZone()
    {
        $this->instance->setAvailabilityZone("az1.test");

        $this->assertEquals(
            "az1.test",
            $this->instance->getAvailabilityZone()
        );
    }

    /**
     * @test
     */
    public function setLifecycleState()
    {
        $this->instance->setLifecycleState("Pending:Wait");

        $this->assertEquals(
            "Pending:Wait",
            $this->instance->getLifecycleState()
        );
    }

    /**
     * @test
     * @dataProvider invalidLifecycleStates
     * @expectedException \InvalidArgumentException
     */
    public function setLifecycleStateInvalid($data)
    {
        $this->instance->setLifecycleState($data);
    }

    public function invalidLifecycleStates()
    {
        return [
            [ "" ],
            [ "test" ]
        ];
    }

    /**
     * @test
     */
    public function setHealthStatus()
    {
        $this->instance->setHealthStatus("healthStatus.test");

        $this->assertEquals(
            "healthStatus.test",
            $this->instance->getHealthStatus()
        );
    }

    /**
     * @test
     */
    public function setLaunchConfigurationName()
    {
        $this->instance->setLaunchConfigurationName("launchConfigurationName.test");

        $this->assertEquals(
            "launchConfigurationName.test",
            $this->instance->getLaunchConfigurationName()
        );
    }

    /**
     * @test
     */
    public function setProtectedFromScaleIn()
    {
        $this->instance->setProtectedFromScaleIn(true);

        $this->assertTrue($this->instance->isProtectedFromScaleIn());
    }

    /**
     * @test
     */
    public function setAutoScalingGroupName()
    {
        $this->instance->setAutoScalingGroupName("autoScalingGroupName.test");

        $this->assertEquals(
            "autoScalingGroupName.test",
            $this->instance->getAutoScalingGroupName()
        );
    }
}
