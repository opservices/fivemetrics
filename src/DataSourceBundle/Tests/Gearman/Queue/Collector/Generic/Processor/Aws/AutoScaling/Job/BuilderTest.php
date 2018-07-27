<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 21/03/17
 * Time: 08:18
 */

namespace DataSourceBundle\Tests\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScaling\Job;

use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScalingGroup\FilterCollection;
use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\JobCollection;
use EssentialsBundle\Entity\Account\Account;
use DataSourceBundle\Entity\Aws\Region\RegionProvider;
use DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScalingGroup\Filter;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScaling\Job\Builder;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScaling\Job\Job;
use EssentialsBundle\Entity\DateTime\DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Class BuilderTest
 * @package DataSourceBundle\Tests\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScaling\Job
 */
class BuilderTest extends TestCase
{
    /**
     * @var Builder
     */
    protected $builder;

    public function setUp()
    {
        $this->builder = new Builder();
    }

    /**
     * @test
     */
    public function buildJobForOneFilterWithoutActivities()
    {
        $job = $this->getAutoScalingJobTest();

        $filters = new FilterCollection([ new Filter(
            'AutoScaling',
            [ 'InstanceState', 'Instance' ]
        ) ]);

        $dataLoader = $this->getMockBuilder(
            'DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScaling\DataLoader'
        )->disableOriginalConstructor()
        ->getMock();

        $expectedResult = new JobCollection([
            $this->getAutoScalingJobTest()
        ]);

        $this->assertEquals(
            $expectedResult,
            $this->builder->build($dataLoader, $job, $filters)
        );
    }

    /**
     * @test
     */
    public function buildJobForMoreThanOneFilter()
    {
        $job = $this->getAutoScalingJobTest();

        $filters = new FilterCollection([
            new Filter(
                'AutoScaling',
                [ 'InstanceState', 'Instance' ]
            ),
            new Filter(
                'AutoScaling',
                [ 'Activities' ]
            )
        ]);

        $dataLoader = $this->getMockBuilder(
            'DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScaling\DataLoader'
        )->disableOriginalConstructor()
        ->getMock();

        $resultJob1 = $this->getAutoScalingJobTest()
            ->setData(new FilterCollection([ $filters->at(0) ]));

        $resultJob2 = $this->getAutoScalingJobTest()
            ->setData(new FilterCollection([ $filters->at(1) ]));

        $expectedResult = new JobCollection([
            $resultJob1,
            $resultJob2
        ]);

        $result = $this->builder->build($dataLoader, $job, $filters);

        $this->assertEquals(
            $expectedResult,
            $result
        );
    }

    /**
     * @test
     */
    public function buildActivitiesJobs()
    {
        $job = $this->getAutoScalingJobTest();

        $filters = new FilterCollection([
            new Filter(
                'AutoScaling',
                [ 'Activities' ]
            )
        ]);

        $dataLoader = $this->getMockBuilder(
            'DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScaling\DataLoader'
        )->disableOriginalConstructor()
        ->setMethods([ 'retrieveAutoScalingGroups' ])
        ->getMock();

        $dataLoader->expects($this->once())
            ->method('retrieveAutoScalingGroups')
            ->willReturn($this->getAutoScalingGroupsTest());

        $resultJob = $this->getAutoScalingJobTest()
            ->setData($filters);

        $expectedResult = new JobCollection([
            $resultJob
        ]);

        $this->assertEquals(
            $expectedResult,
            $this->builder->build($dataLoader, $job, $filters)
        );
    }

    public function getAutoScalingJobTest()
    {
        $account = new Account();
        $account->setUid('unitTest');

        return new Job(
            $account,
            DateTime::createFromFormat(
                'Y-m-d H:i:s',
                '2017-08-07 14:10:00'
            ),
            RegionProvider::factory('us-east-1'),
            'test',
            'unit'
        );
    }

    public function getAutoScalingGroupsTest()
    {
        return \DataSourceBundle\Entity\Aws\AutoScaling\Builder::buildAutoScalingGroups(
            json_decode('[
                {
                    "AutoScalingGroupARN": "arn:aws:autoscaling:us-east-1:...",
                    "AutoScalingGroupName": "hmg.dt.loadbalance.ha",
                    "AvailabilityZones": [
                        "us-east-1b",
                        "us-east-1e"
                    ],
                    "CreatedTime": "2016-06-01T21:06:28+00:00",
                    "DefaultCooldown": 300,
                    "DesiredCapacity": 0,
                    "EnabledMetrics": [],
                    "HealthCheckGracePeriod": 300,
                    "HealthCheckType": "ELB",
                    "Instances": [],
                    "LaunchConfigurationName": "hmg.dt.loadbalance.ha_2016-06-30",
                    "LoadBalancerNames": [
                        "hmg-dt-loadbalance-ha"
                    ],
                    "MaxSize": 1,
                    "MinSize": 0,
                    "NewInstancesProtectedFromScaleIn": false,
                    "SuspendedProcesses": [],
                    "Tags": [
                        {
                            "Key": "Environment",
                            "PropagateAtLaunch": true,
                            "ResourceId": "hmg.dt.loadbalance.ha",
                            "ResourceType": "auto-scaling-group",
                            "Value": "hmg"
                        },
                        {
                            "Key": "Name",
                            "PropagateAtLaunch": true,
                            "ResourceId": "hmg.dt.loadbalance.ha",
                            "ResourceType": "auto-scaling-group",
                            "Value": "HA-hmg"
                        },
                        {
                            "Key": "dt.loadbalance.ha",
                            "PropagateAtLaunch": true,
                            "ResourceId": "hmg.dt.loadbalance.ha",
                            "ResourceType": "auto-scaling-group",
                            "Value": "hmg"
                        }
                    ],
                    "TargetGroupARNs": [],
                    "TerminationPolicies": [
                        "Default"
                    ],
                    "VPCZoneIdentifier": "subnet-6bc50240,subnet-911cc6c8"
                }
            ]', true)
        );
    }
}
