<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 09/02/17
 * Time: 16:17
 */

namespace DataSourceBundle\Aws\EC2\Measurement\ElasticLoadBalancer;

use DataSourceBundle\Collection\Aws\EC2\Instance\InstanceCollection;
use DataSourceBundle\Collection\Aws\ElasticLoadBalancer\ElasticLoadBalancerCollection;
use DataSourceBundle\Entity\Aws\EC2\Instance\Instance;
use DataSourceBundle\Entity\Aws\Region\RegionInterface;
use DataSourceBundle\Entity\Aws\ElasticLoadBalancer\ElasticLoadBalancer;
use DataSourceBundle\Entity\Aws\ElasticLoadBalancer\InstanceHealth;
use EssentialsBundle\Collection\Metric\MetricCollection;
use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\Entity\Metric\Builder;

/**
 * Class InstanceState
 * @package DataSourceBundle\Aws\EC2\Measurement\ElasticLoadBalancer
 */
class Instances extends MeasurementAbstract
{
    /**
     * @var InstanceCollection
     */
    protected $instances;

    /**
     * InstanceState constructor.
     * @param RegionInterface $region
     * @param DateTime $dateTime
     * @param ElasticLoadBalancerCollection $groups
     * @param InstanceCollection|null $instances
     */
    public function __construct(
        RegionInterface $region,
        DateTime $dateTime,
        ElasticLoadBalancerCollection $groups,
        InstanceCollection $instances = null
    ) {
        parent::__construct($region, $dateTime, $groups);
        $this->setInstances($instances ?: new InstanceCollection);
    }

    /**
     * @return InstanceCollection
     */
    public function getInstances(): InstanceCollection
    {
        return $this->instances;
    }

    /**
     * @param InstanceCollection $instances
     * @return Instances
     */
    public function setInstances(InstanceCollection $instances): Instances
    {
        $this->instances = $instances;
        return $this;
    }

    /**
     * @return MetricCollection
     */
    public function getMetrics(): MetricCollection
    {
        $buildData = [];
        $elbs = $this->getElasticLoadBalancers();
        foreach ($elbs as $elb) {
            foreach ($elb->getInstanceHealth() as $health) {
                $instance = $this->getInstances()->find($health->getInstanceId());
                if (is_null($instance)) {
                    continue;
                }

                $key = $this->generateUniqueKey($elb, $health, $instance);
                if (!isset($buildData["$key"])) {
                    $buildData["$key"] = [
                        'name' => $this->getName(['instances']),
                        'tags' => $this->getTags($elb, $health, $instance),
                        'points' => [
                            [
                                'value' => 0,
                                'time' => $this->getMetricsDatetime()
                            ]
                        ]
                    ];
                }

                $buildData["$key"]['points'][0]['value']++;
            }
        }

        return Builder::build(array_values($buildData));
    }

    protected function generateUniqueKey(
        ElasticLoadBalancer $elb,
        InstanceHealth $health,
        Instance $instance
    ): string {
        return $health->getState()
            . $instance->getPlacement()->getAvailabilityZone()
            . $instance->getInstanceId()
            . $elb->getLoadBalancerName();
    }

    /**
     * @param ElasticLoadBalancer $elb
     * @param InstanceHealth $health
     * @param string $availabilityZone
     * @return array
     */
    protected function getTags(
        ElasticLoadBalancer $elb,
        InstanceHealth $health,
        Instance $instance
    ): array {

        return [
            [
                'key' => '::fm::region',
                'value' => $this->getRegion()->getCode()
            ],
            [
                'key' => '::fm::availabilityZone',
                'value' => $instance->getPlacement()->getAvailabilityZone()
            ],
            [
                'key' => '::fm::elbName',
                'value' => $elb->getLoadBalancerName()
            ],
            [
                'key' => '::fm::state',
                'value' => $health->getState()
            ],
        ];
    }
}
