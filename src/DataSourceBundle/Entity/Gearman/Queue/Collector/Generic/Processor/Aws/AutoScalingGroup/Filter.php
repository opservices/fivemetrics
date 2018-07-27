<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 07/03/17
 * Time: 18:38
 */

namespace DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScalingGroup;

use DataSourceBundle\Collection\Aws\EC2\Instance\InstanceCollection;
use DataSourceBundle\Entity\Aws\AutoScaling\AutoScalingGroup;
use DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\Filter as AwsFilter;

/**
 * Class Filter
 * @package DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScalingGroup
 */
class Filter extends AwsFilter
{
    /**
     * @var InstanceCollection
     */
    protected $instances;

    /**
     * @var AutoScalingGroup
     */
    protected $autoScalingGroup;

    /**
     * Filter constructor.
     * @param string $namespace
     * @param array $measurementNames
     * @param InstanceCollection|null $instances
     * @param AutoScalingGroup|null $autoScalingGroup
     */
    public function __construct(
        $namespace,
        array $measurementNames,
        InstanceCollection $instances = null,
        AutoScalingGroup $autoScalingGroup = null
    ) {
        parent::__construct($namespace, $measurementNames);

        (is_null($instances)) ?: $this->setInstances($instances);
        (is_null($autoScalingGroup)) ?: $this->setAutoScalingGroup($autoScalingGroup);
    }

    /**
     * @return InstanceCollection|null
     */
    public function getInstances()
    {
        return $this->instances;
    }

    /**
     * @param InstanceCollection $instances
     * @return Filter
     */
    public function setInstances(InstanceCollection $instances): Filter
    {
        $this->instances = $instances;
        return $this;
    }

    /**
     * @return AutoScalingGroup|null
     */
    public function getAutoScalingGroup()
    {
        return $this->autoScalingGroup;
    }

    /**
     * @param AutoScalingGroup $autoScalingGroup
     * @return Filter
     */
    public function setAutoScalingGroup(AutoScalingGroup $autoScalingGroup): Filter
    {
        $this->autoScalingGroup = $autoScalingGroup;
        return $this;
    }
}
