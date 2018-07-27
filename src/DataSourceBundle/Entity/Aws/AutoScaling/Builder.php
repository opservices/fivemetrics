<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 03/02/17
 * Time: 11:07
 */

namespace DataSourceBundle\Entity\Aws\AutoScaling;

use DataSourceBundle\Collection\Aws\AutoScaling\ActivityCollection;
use DataSourceBundle\Collection\Aws\AutoScaling\AutoScalingGroupCollection;
use DataSourceBundle\Collection\Aws\AutoScaling\EnabledMetricCollection;
use DataSourceBundle\Collection\Aws\AutoScaling\InstanceCollection;
use DataSourceBundle\Collection\Aws\AutoScaling\SuspendedProcessCollection;
use DataSourceBundle\Collection\Aws\AutoScaling\TagCollection;
use EssentialsBundle\Entity\DateTime\DateTime;

/**
 * Class Builder
 * @package DataSourceBundle\Entity\Aws\AutoScaling
 */
class Builder
{
    /**
     * @param array $data
     * @return AutoScalingGroupCollection
     */
    public static function buildAutoScalingGroups(
        array $data
    ): AutoScalingGroupCollection {
        $autoScalingGroups = new AutoScalingGroupCollection();
        foreach ($data as $atGroup) {
            $autoScalingGroups->add(
                new AutoScalingGroup(
                    $atGroup['AutoScalingGroupName'],
                    $atGroup['AutoScalingGroupARN'],
                    $atGroup['LaunchConfigurationName'],
                    $atGroup['MinSize'],
                    $atGroup['MaxSize'],
                    $atGroup['DesiredCapacity'],
                    $atGroup['DefaultCooldown'],
                    $atGroup['AvailabilityZones'],
                    $atGroup['LoadBalancerNames'],
                    $atGroup['TargetGroupARNs'],
                    $atGroup['HealthCheckType'],
                    $atGroup['HealthCheckGracePeriod'],
                    new DateTime($atGroup['CreatedTime']),
                    $atGroup['VPCZoneIdentifier'],
                    $atGroup['TerminationPolicies'],
                    $atGroup['NewInstancesProtectedFromScaleIn'],
                    self::buildInstanceCollection(
                        $atGroup['Instances'],
                        $atGroup['AutoScalingGroupName']
                    ),
                    self::buildSuspendedProcessCollection(
                        $atGroup['SuspendedProcesses']
                    ),
                    self::buildEnabledMetricCollection(
                        $atGroup['EnabledMetrics']
                    ),
                    self::buildTagCollection($atGroup['Tags']),
                    (empty($atGroup['Activities'])) ? null : self::buildActivities($atGroup['Activities'])
                )
            );
        }

        return $autoScalingGroups;
    }

    /**
     * @param array $data
     * @return ActivityCollection
     */
    public static function buildActivities(array $data): ActivityCollection
    {
        $activities = new ActivityCollection();

        foreach ($data as $activity) {
            $activities->add(
                new Activity(
                    $activity['ActivityId'],
                    $activity['AutoScalingGroupName'],
                    $activity['Description'],
                    $activity['Cause'],
                    new DateTime($activity['StartTime']),
                    new DateTime($activity['EndTime']),
                    $activity['StatusCode'],
                    $activity['Progress'],
                    $activity['Details']
                )
            );
        }

        return $activities;
    }

    /**
     * @param array $data
     * @return TagCollection
     */
    protected static function buildTagCollection(array $data): TagCollection
    {
        $tagCollection = new TagCollection();

        foreach ($data as $tag) {
            $tagCollection->add(
                new Tag(
                    $tag['ResourceId'],
                    $tag['ResourceType'],
                    $tag['PropagateAtLaunch'],
                    $tag['Key'],
                    $tag['Value']
                )
            );
        }

        return $tagCollection;
    }

    /**
     * @param array $data
     * @return EnabledMetricCollection
     */
    protected static function buildEnabledMetricCollection(
        array $data
    ): EnabledMetricCollection {
        $enabledMetricCollection = new EnabledMetricCollection();

        foreach ($data as $enabledMetric) {
            $enabledMetricCollection->add(
                new EnabledMetric(
                    $enabledMetric['Granularity'],
                    $enabledMetric['Metric']
                )
            );
        }

        return $enabledMetricCollection;
    }

    /**
     * @param array $data
     * @return SuspendedProcessCollection
     */
    protected static function buildSuspendedProcessCollection(
        array $data
    ): SuspendedProcessCollection {
        $suspendedProcesses = new SuspendedProcessCollection();

        foreach ($data as $suspendedProcess) {
            $suspendedProcesses->add(
                new SuspendedProcess(
                    $suspendedProcess['ProcessName'],
                    $suspendedProcess['SuspensionReason']
                )
            );
        }

        return $suspendedProcesses;
    }

    /**
     * @param array $data
     * @param string $groupName
     * @return InstanceCollection
     */
    public static function buildInstanceCollection(
        array $data,
        string $groupName = ''
    ): InstanceCollection {
        $instances = new InstanceCollection();

        foreach ($data as $instance) {
            $instances->add(
                new Instance(
                    $instance['InstanceId'],
                    $instance['AvailabilityZone'],
                    $instance['LifecycleState'],
                    $instance['HealthStatus'],
                    $instance['LaunchConfigurationName'] ?? '',
                    $instance['ProtectedFromScaleIn'],
                    self::getAutoScalingGroupName(
                        $groupName,
                        $instance['AutoScalingGroupName']
                    )
                )
            );
        }

        return $instances;
    }

    /**
     * @param string $autoScalingGroupName
     * @param string $instanceAutoScalingGroupName
     * @return string
     */
    protected static function getAutoScalingGroupName(
        $autoScalingGroupName = '',
        $instanceAutoScalingGroupName = ''
    ): string {
        return (empty($autoScalingGroupName))
            ? $instanceAutoScalingGroupName
            : $autoScalingGroupName;
    }
}
