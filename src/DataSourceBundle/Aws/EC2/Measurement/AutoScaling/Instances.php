<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 09/02/17
 * Time: 16:34
 */

namespace DataSourceBundle\Aws\EC2\Measurement\AutoScaling;

use EssentialsBundle\Collection\Metric\MetricCollection;
use DataSourceBundle\Entity\Aws\AutoScaling\AutoScalingGroup;
use DataSourceBundle\Entity\Aws\AutoScaling\Instance;
use EssentialsBundle\Entity\Metric\Builder;

/**
 * Class InstanceState
 * @package DataSourceBundle\Aws\EC2\Measurement\AutoScaling
 */
class Instances extends MeasurementAbstract
{
    /**
     * @return MetricCollection
     */
    public function getMetrics(): MetricCollection
    {
        $buildData = [];
        foreach ($this->getAutoScalingGroups() as $group) {
            foreach ($group->getInstances() as $ec2) {
                $key = $this->generateUniqueKey($group, $ec2);
                if (!isset($buildData[$key])) {
                    $buildData[$key] = [
                        'name' => $this->getName(['instances']),
                        'tags' => $this->getMetricTags($group, $ec2),
                        'points' => [
                            [
                                'value' => 0,
                                'time' => $this->getMetricsDatetime()
                            ]
                        ]
                    ];
                }

                $buildData[$key]['points'][0]['value']++;
            }
        }

        return Builder::build(array_values($buildData));
    }

    protected function generateUniqueKey(
        AutoScalingGroup $group,
        Instance $ec2
    ): string {
        return $group->getAutoScalingGroupName()
            . $ec2->getInstanceId()
            . $ec2->getLifecycleState()
            . $ec2->getAvailabilityZone();
    }

    /**
     * @param AutoScalingGroup $group
     * @param Instance $ec2
     * @return array
     */
    protected function getMetricTags(
        AutoScalingGroup $group,
        Instance $ec2
    ): array {
        $tags = [
            [
                'key' => '::fm::region',
                'value' => $this->getRegion()->getCode()
            ],
            [
                'key' => '::fm::availabilityZone',
                'value' => $ec2->getAvailabilityZone()
            ],
            [
                'key' => '::fm::groupName',
                'value' => $group->getAutoScalingGroupName()
            ],
            [
                'key' => '::fm::state',
                'value' => $ec2->getLifecycleState()
            ],
            [
                'key' => '::fm::healthStatus',
                'value' => $ec2->getHealthStatus()
            ],
        ];

        $awsTags = array_map(function ($tag) {
            return [
                'key'   => $tag['Key'],
                'value' => $tag['Value'],
            ];
        }, $group->getTags()->toArray());

        return array_merge($tags, $awsTags);
    }
}
