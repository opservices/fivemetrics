<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 09/02/17
 * Time: 16:35
 */

namespace DataSourceBundle\Aws\EC2\Measurement\ElasticLoadBalancer;

use DataSourceBundle\Aws\MeasurementInterface;
use DataSourceBundle\Collection\Aws\ElasticLoadBalancer\ElasticLoadBalancerCollection;
use DataSourceBundle\Entity\Aws\Region\RegionInterface;
use EssentialsBundle\Entity\DateTime\DateTime;

/**
 * Class MeasurementAbstract
 * @package DataSourceBundle\Aws\EC2\Measurement\ElasticLoadBalancer
 */
abstract class MeasurementAbstract
    extends \DataSourceBundle\Aws\MeasurementAbstract
    implements MeasurementInterface
{
    /**
     * @var ElasticLoadBalancerCollection
     */
    protected $elbs;

    /**
     * MeasurementAbstract constructor.
     * @param RegionInterface $region
     * @param ElasticLoadBalancerCollection $elbs
     */
    public function __construct(
        RegionInterface $region,
        DateTime $dateTime,
        ElasticLoadBalancerCollection $elbs
    ) {
        parent::__construct($region, $dateTime);
        $this->setElasticLoadBalancers($elbs);
    }

    /**
     * @return ElasticLoadBalancerCollection
     */
    public function getElasticLoadBalancers(): ElasticLoadBalancerCollection
    {
        return $this->elbs;
    }

    /**
     * @param ElasticLoadBalancerCollection $elbs
     * @return MeasurementAbstract
     */
    public function setElasticLoadBalancers(ElasticLoadBalancerCollection $elbs): MeasurementAbstract
    {
        $this->elbs = $elbs;
        return $this;
    }

    /**
     * @return array
     */
    protected function getNameParts(): array
    {
        return array_merge(
            parent::getNameParts(),
            [ 'ec2', 'elb' ]
        );
    }

    /**
     * @return array
     */
    protected function getTags(): array
    {
        $tags = parent::getTags();

        $tags[] = [
            'key' => '::fm::region',
            'value' => $this->getRegion()->getCode()
        ];

        return $tags;
    }
}
