<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 07/03/17
 * Time: 18:38
 */

namespace DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer;

use DataSourceBundle\Collection\Aws\EC2\Instance\InstanceCollection;
use DataSourceBundle\Collection\Aws\ElasticLoadBalancer\ElasticLoadBalancerCollection;
use DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\Filter as AwsFilter;

/**
 * Class Filter
 * @package DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer
 */
class Filter extends AwsFilter
{
    /**
     * @var InstanceCollection
     */
    protected $instances;

    /**
     * @var ElasticLoadBalancerCollection
     */
    protected $elbs;

    /**
     * Filter constructor.
     * @param string $namespace
     * @param array $measurementNames
     * @param InstanceCollection|null $instances
     * @param ElasticLoadBalancerCollection|null $elbs
     */
    public function __construct(
        $namespace,
        array $measurementNames,
        ElasticLoadBalancerCollection $elbs = null
    ) {
        parent::__construct($namespace, $measurementNames);

        (is_null($elbs)) ?: $this->setElbs($elbs);
    }

    /**
     * @return ElasticLoadBalancerCollection|null
     */
    public function getElbs()
    {
        return $this->elbs;
    }

    /**
     * @param ElasticLoadBalancerCollection $elbs
     * @return Filter
     */
    public function setElbs(ElasticLoadBalancerCollection $elbs): Filter
    {
        $this->elbs = $elbs;
        return $this;
    }
}
