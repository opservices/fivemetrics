<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 07/08/17
 * Time: 16:17
 */

namespace DataSourceBundle\Aws\EC2\Measurement\EC2;

use DataSourceBundle\Aws\MeasurementAbstract;
use DataSourceBundle\Aws\MeasurementInterface;
use DataSourceBundle\Collection\Aws\EC2\Instance\InstanceCollection;
use DataSourceBundle\Entity\Aws\EC2\Instance\Instance as InstanceEntity;
use DataSourceBundle\Entity\Aws\Region\RegionInterface;
use EssentialsBundle\Collection\Metric\MetricCollection;
use EssentialsBundle\Entity\Metric\Builder;
use EssentialsBundle\Entity\DateTime\DateTime;

class Instances extends MeasurementAbstract implements MeasurementInterface
{
    /**
     * @var InstanceCollection
     */
    protected $instances;

    /**
     * Instances constructor.
     * @param RegionInterface $region
     * @param DateTime $dateTime
     * @param InstanceCollection $instances
     */
    public function __construct(
        RegionInterface $region,
        DateTime $dateTime,
        InstanceCollection $instances
    ) {
        parent::__construct($region, $dateTime);
        $this->instances = $instances;
    }

    /**
     * @return InstanceCollection
     */
    public function getInstances(): InstanceCollection
    {
        return $this->instances;
    }

    /**
     * @return array
     */
    protected function getNameParts(): array
    {
        return array_merge(parent::getNameParts(), [ 'ec2' ]);
    }

    public function getMetrics(): MetricCollection
    {
        $buildData = [];
        $instances = $this->getInstances();

        foreach ($instances as $instance) {
            $key = md5(json_encode($instance));

            if (isset($buildData[$key])) {
                $buildData[$key]['points'][0]['value']++;
                continue;
            }

            $buildData[$key] = [
                'name' => $this->getName([ 'instances' ]),
                'tags' => $this->getTags($instance),
                'points' => [
                    [
                        'value' => 1,
                        'time' => $this->getMetricsDatetime()
                    ]
                ]
            ];
        }

        return Builder::build(array_values($buildData));
    }

    /**
     * @param InstanceEntity $instance
     * @return array
     */
    protected function getTags(InstanceEntity $instance): array
    {
        $tags = [
            [
                'key' => '::fm::region',
                'value' => $this->getRegion()->getCode()
            ],
            [
                'key' => '::fm::availabilityZone',
                'value' => $instance->getPlacement()->getAvailabilityZone()
            ],
            [
                'key' => '::fm::state',
                'value' => $instance->getState()->getName()
            ],
            [
                'key' => '::fm::instanceType',
                'value' => $instance->getInstanceType()
            ],
            [
                'key' => '::fm::placementGroup',
                'value' => $instance->getPlacement()->getGroupName()
            ],
        ];

        $awsTags = array_map(function ($tag) {
            return [
                'key'   => $tag['Key'],
                'value' => $tag['Value'],
            ];
        }, $instance->getTags()->toArray());

        return array_merge($tags, $awsTags);
    }
}
