<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 16/02/17
 * Time: 14:11
 */

namespace DataSourceBundle\Tests\Entity\Aws\AutoScaling;

use DataSourceBundle\Collection\Aws\AutoScaling\ActivityCollection;
use DataSourceBundle\Collection\Aws\AutoScaling\EnabledMetricCollection;
use DataSourceBundle\Collection\Aws\AutoScaling\InstanceCollection;
use DataSourceBundle\Collection\Aws\AutoScaling\SuspendedProcessCollection;
use DataSourceBundle\Collection\Aws\AutoScaling\TagCollection;
use DataSourceBundle\Entity\Aws\AutoScaling\Activity;
use DataSourceBundle\Entity\Aws\AutoScaling\AutoScalingGroup;
use DataSourceBundle\Entity\Aws\AutoScaling\EnabledMetric;
use DataSourceBundle\Entity\Aws\AutoScaling\Instance;
use DataSourceBundle\Entity\Aws\AutoScaling\SuspendedProcess;
use DataSourceBundle\Entity\Aws\AutoScaling\Tag;
use EssentialsBundle\Entity\DateTime\DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Class AutoScalingGroupTest
 * @package Test\Entity\Aws\AutoScaling
 */
class AutoScalingGroupTest extends TestCase
{
    /**
     * @var AutoScalingGroup
     */
    protected $atGroup;

    public function setUp()
    {
        $this->atGroup = new AutoScalingGroup(
            "autoScalingGroupName",
            "autoScalingGroupARN",
            "launchConfigurationName",
            2,
            4,
            3,
            300,
            [ "az1", "az2" ],
            [ "lb1", "lb2" ],
            [ "targetGroupARNs" ],
            "healthCheckType",
            60,
            DateTime::createFromFormat('Y-m-d H:i', '2017-02-16 14:17'),
            "VPCZoneIdentifier",
            [ "terminationPolicies" ],
            false,
            new InstanceCollection(),
            new SuspendedProcessCollection(),
            new EnabledMetricCollection(),
            new TagCollection(),
            new ActivityCollection()
        );
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals(
            "autoScalingGroupName",
            $this->atGroup->getAutoScalingGroupName()
        );

        $this->assertEquals(
            "autoScalingGroupARN",
            $this->atGroup->getAutoScalingGroupARN()
        );

        $this->assertEquals(
            "launchConfigurationName",
            $this->atGroup->getLaunchConfigurationName()
        );

        $this->assertEquals(
            2,
            $this->atGroup->getMinSize()
        );

        $this->assertEquals(
            4,
            $this->atGroup->getMaxSize()
        );

        $this->assertEquals(
            3,
            $this->atGroup->getDesiredCapacity()
        );

        $this->assertEquals(
            300,
            $this->atGroup->getDefaultCooldown()
        );

        $this->assertEquals(
            [ "az1", "az2" ],
            $this->atGroup->getAvailabilityZones()
        );

        $this->assertEquals(
            [ "lb1", "lb2" ],
            $this->atGroup->getLoadBalancerNames()
        );

        $this->assertEquals(
            [ "targetGroupARNs" ],
            $this->atGroup->getTargetGroupARNs()
        );

        $this->assertEquals(
            "healthCheckType",
            $this->atGroup->getHealthCheckType()
        );

        $this->assertEquals(
            60,
            $this->atGroup->getHealthCheckGracePeriod()
        );

        $this->assertEquals(
            '2017-02-16 14:17',
            $this->atGroup->getCreatedTime()->format('Y-m-d H:i')
        );

        $this->assertEquals(
            "VPCZoneIdentifier",
            $this->atGroup->getVPCZoneIdentifier()
        );

        $this->assertEquals(
            [ "terminationPolicies" ],
            $this->atGroup->getTerminationPolicies()
        );

        $this->assertFalse(
            $this->atGroup->isNewInstancesProtectedFromScaleIn()
        );

        $this->assertInstanceOf(
            'DataSourceBundle\Collection\Aws\AutoScaling\InstanceCollection',
            $this->atGroup->getInstances()
        );

        $this->assertInstanceOf(
            'DataSourceBundle\Collection\Aws\AutoScaling\SuspendedProcessCollection',
            $this->atGroup->getSuspendedProcesses()
        );

        $this->assertInstanceOf(
            'DataSourceBundle\Collection\Aws\AutoScaling\EnabledMetricCollection',
            $this->atGroup->getEnabledMetrics()
        );

        $this->assertInstanceOf(
            'DataSourceBundle\Collection\Aws\AutoScaling\TagCollection',
            $this->atGroup->getTags()
        );

        $this->assertInstanceOf(
            'DataSourceBundle\Collection\Aws\AutoScaling\ActivityCollection',
            $this->atGroup->getActivities()
        );
    }

    /**
     * @test
     */
    public function setAutoScalingGroupName()
    {
        $this->atGroup->setAutoScalingGroupName("autoScalingGroupName.test");

        $this->assertEquals(
            "autoScalingGroupName.test",
            $this->atGroup->getAutoScalingGroupName()
        );
    }

    /**
     * @test
     */
    public function setAutoScalingGroupARN()
    {
        $this->atGroup->setAutoScalingGroupARN(
            "autoScalingGroupARN.test"
        );

        $this->assertEquals(
            "autoScalingGroupARN.test",
            $this->atGroup->getAutoScalingGroupARN()
        );
    }

    /**
     * @test
     */
    public function setLaunchConfigurationName()
    {
        $this->atGroup->setLaunchConfigurationName(
            "launchConfigurationName.test"
        );

        $this->assertEquals(
            "launchConfigurationName.test",
            $this->atGroup->getLaunchConfigurationName()
        );
    }

    /**
     * @test
     */
    public function setMinSize()
    {
        $this->atGroup->setMinSize(4);

        $this->assertEquals(
            4,
            $this->atGroup->getMinSize()
        );
    }

    /**
     * @test
     */
    public function setMaxSize()
    {
        $this->atGroup->setMaxSize(8);

        $this->assertEquals(
            8,
            $this->atGroup->getMaxSize()
        );
    }

    /**
     * @test
     */
    public function setDesiredCapacity()
    {
        $this->atGroup->setDesiredCapacity(6);

        $this->assertEquals(
            6,
            $this->atGroup->getDesiredCapacity()
        );
    }

    /**
     * @test
     */
    public function setDefaultCooldown()
    {
        $this->atGroup->setDefaultCooldown(600);

        $this->assertEquals(
            600,
            $this->atGroup->getDefaultCooldown()
        );
    }

    /**
     * @test
     */
    public function setAvailabilityZones()
    {
        $this->atGroup->setAvailabilityZones(["az1.test", "az2.test"]);

        $this->assertEquals(
            ["az1.test", "az2.test"],
            $this->atGroup->getAvailabilityZones()
        );
    }

    /**
     * @test
     */
    public function setLoadBalancerNames()
    {
        $this->atGroup->setLoadBalancerNames([ "lb1.test", "lb2.test" ]);

        $this->assertEquals(
            [ "lb1.test", "lb2.test" ],
            $this->atGroup->getLoadBalancerNames()
        );
    }

    /**
     * @test
     */
    public function setTargetGroupARNs()
    {
        $this->atGroup->setTargetGroupARNs([ "targetGroupARNs.test" ]);

        $this->assertEquals(
            [ "targetGroupARNs.test" ],
            $this->atGroup->getTargetGroupARNs()
        );
    }

    /**
     * @test
     */
    public function setHealthCheckType()
    {
        $this->atGroup->setHealthCheckType("healthCheckType.test");

        $this->assertEquals(
            "healthCheckType.test",
            $this->atGroup->getHealthCheckType()
        );
    }

    /**
     * @test
     */
    public function setHealthCheckGracePeriod()
    {
        $this->atGroup->setHealthCheckGracePeriod(120);

        $this->assertEquals(
            120,
            $this->atGroup->getHealthCheckGracePeriod()
        );
    }

    /**
     * @test
     */
    public function setCreatedTime()
    {
        $this->atGroup->setCreatedTime(
            DateTime::createFromFormat(
                'Y-m-d H:i',
                '2017-02-16 14:52'
            )
        );

        $this->assertEquals(
            '2017-02-16 14:52',
            $this->atGroup->getCreatedTime()->format('Y-m-d H:i')
        );
    }

    /**
     * @test
     */
    public function setVPCZoneIdentifier()
    {
        $this->atGroup->setVPCZoneIdentifier("VPCZoneIdentifier.test");

        $this->assertEquals(
            "VPCZoneIdentifier.test",
            $this->atGroup->getVPCZoneIdentifier()
        );
    }

    /**
     * @test
     */
    public function setTerminationPolicies()
    {
        $this->atGroup->setTerminationPolicies([ "terminationPolicies.test" ]);

        $this->assertEquals(
            [ "terminationPolicies.test" ],
            $this->atGroup->getTerminationPolicies()
        );
    }

    /**
     * @test
     */
    public function setNewInstancesProtectedFromScaleIn()
    {
        $this->atGroup->setNewInstancesProtectedFromScaleIn(true);

        $this->assertTrue(
            $this->atGroup->isNewInstancesProtectedFromScaleIn()
        );
    }

    /**
     * @test
     */
    public function setInstances()
    {
        $instance = new Instance(
            "instanceId",
            "az1",
            "Pending",
            "healthStatus",
            "launchConfigurationName",
            false,
            "autoScalingGroupName"
        );

        $instances = new InstanceCollection();
        $instances->add($instance);

        $this->atGroup->setInstances($instances);

        $this->assertEquals(
            $instance,
            $this->atGroup->getInstances()->current()
        );
    }

    /**
     * @test
     */
    public function setSuspendedProcesses()
    {
        $suspendedProcess = new SuspendedProcess(
            "processName",
            "suspensionReason"
        );

        $suspendedProcesses = new SuspendedProcessCollection();
        $suspendedProcesses->add($suspendedProcess);

        $this->atGroup->setSuspendedProcesses($suspendedProcesses);

        $this->assertEquals(
            $suspendedProcess,
            $this->atGroup->getSuspendedProcesses()->current()
        );
    }

    /**
     * @test
     */
    public function setEnabledMetrics()
    {
        $enabledMetric = new EnabledMetric(
            "granularity",
            "metric"
        );

        $enabledMetrics = new EnabledMetricCollection();
        $enabledMetrics->add($enabledMetric);

        $this->atGroup->setEnabledMetrics($enabledMetrics);

        $this->assertEquals(
            $enabledMetric,
            $this->atGroup->getEnabledMetrics()->current()
        );
    }

    /**
     * @test
     */
    public function setTags()
    {
        $tag = new Tag(
            "resourceId",
            "resourceType",
            true,
            "key",
            "value"
        );

        $tags = new TagCollection();
        $tags->add($tag);

        $this->atGroup->setTags($tags);

        $this->assertEquals(
            $tag,
            $this->atGroup->getTags()->current()
        );
    }

    /**
     * @test
     */
    public function setActivities()
    {
        $activity = new Activity(
            "activityId",
            "autoScalingGroupName",
            "description",
            "cause",
            DateTime::createFromFormat('Y-m-d H:i', '2017-02-16 15:06'),
            DateTime::createFromFormat('Y-m-d H:i', '2017-02-16 15:07'),
            "PendingSpotBidPlacement",
            10,
            "details"
        );

        $activities = new ActivityCollection();
        $activities->add($activity);

        $this->atGroup->setActivities($activities);

        $this->assertEquals(
            $activity,
            $this->atGroup->getActivities()->current()
        );
    }
}
