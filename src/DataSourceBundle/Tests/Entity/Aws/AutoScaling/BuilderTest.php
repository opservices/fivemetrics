<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 16/02/17
 * Time: 15:12
 */

namespace DataSourceBundle\Tests\Entity\Aws\AutoScaling;

use DataSourceBundle\Entity\Aws\AutoScaling\Builder;
use PHPUnit\Framework\TestCase;
use EssentialsBundle\Reflection;

/**
 * Class BuilderTest
 * @package Test\Entity\Aws\AutoScaling
 */
class BuilderTest extends TestCase
{
    /**
     * @test
     * @dataProvider validAutoScalingData
     * @param $data
     */
    public function buildAutoScaling($data)
    {
        $atGroups = Builder::buildAutoScalingGroups([$data]);

        $this->assertInstanceOf(
            "DataSourceBundle\\Collection\\Aws\\AutoScaling\\AutoScalingGroupCollection",
            $atGroups
        );

        $this->assertGreaterThan(0, count($atGroups));

        $this->assertEquals(
            json_encode($data),
            json_encode($atGroups->current())
        );
    }

    /**
     * @test
     */
    public function getAutoScalingGroupName()
    {
        $builder = new Builder;

        $this->assertEquals(
            "atGroup",
            Reflection::callMethodOnObject(
                $builder,
                "getAutoScalingGroupName",
                [ '', 'atGroup' ]
            )
        );
    }

    public function validAutoScalingData()
    {
        $atGroups = [
            '{
                "AutoScalingGroupName": "AutoScalingGroupName",
                "AutoScalingGroupARN": "AutoScalingGroupARN",
                "MinSize": 2,
                "MaxSize": 4,
                "DesiredCapacity": 3,
                "DefaultCooldown": 300,
                "HealthCheckType": "HealthCheckType",
                "HealthCheckGracePeriod": 120,
                "CreatedTime": "2017-02-16T15:23:32-02:00",
                "VPCZoneIdentifier": "VPCZoneIdentifier",
                "NewInstancesProtectedFromScaleIn": false,
                "TerminationPolicies": [
                    "TerminationPolicies"
                ],
                "AvailabilityZones": [
                    "az1"
                ],
                "LoadBalancerNames": [
                    "lb1"
                ],
                "TargetGroupARNs": [
                    "TargetGroupARNs"
                ],
                "LaunchConfigurationName": "LaunchConfigurationName",
                "Instances": [
                    {
                        "InstanceId": "InstanceId",
                        "AvailabilityZone": "AvailabilityZone",
                        "LifecycleState": "Pending",
                        "HealthStatus": "HealthStatus",
                        "LaunchConfigurationName": "LaunchConfigurationName",
                        "ProtectedFromScaleIn": false,
                        "AutoScalingGroupName": "AutoScalingGroupName"
                    }
                ],
                "SuspendedProcesses": [
                    {
                        "ProcessName": "ProcessName",
                        "SuspensionReason": "SuspensionReason"
                    }
                ],
                "EnabledMetrics": [
                    {
                        "Granularity": "Granularity",
                        "Metric": "Metric"
                    }
                ],
                "Tags": [
                    {
                        "ResourceId": "ResourceId",
                        "ResourceType": "ResourceType",
                        "PropagateAtLaunch": true,
                        "Key": "Key",
                        "Value": "value"
                    }
                ],
                "Activities": [
                    {
                        "ActivityId": "ActivityId",
                        "AutoScalingGroupName": "AutoScalingGroupName",
                        "Description": "Description",
                        "Cause": "Cause",
                        "StartTime": "2017-02-16T15:23:32-02:00",
                        "EndTime": "2017-02-16T15:42:32-02:00",
                        "StatusCode": "PendingSpotBidPlacement",
                        "Progress": 10,
                        "Details": "Details"
                    }
                ]
            }'
        ];

        foreach ($atGroups as $atGroup) {
            yield [ json_decode($atGroup, true) ];
        }
    }
}
