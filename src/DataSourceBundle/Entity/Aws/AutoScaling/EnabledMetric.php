<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 03/02/17
 * Time: 10:45
 */

namespace DataSourceBundle\Entity\Aws\AutoScaling;

use DataSourceBundle\Entity\Aws\EntityAbstract;

/**
 * Class EnabledMetric
 * @package DataSourceBundle\Entity\Aws\AutoScaling
 */
class EnabledMetric extends EntityAbstract
{
    /**
     * @var string
     */
    protected $granularity;

    /**
     * @var string
     */
    protected $metric;

    /**
     * EnabledMetric constructor.
     * @param string $granularity
     * @param string $metric
     */
    public function __construct(
        string $granularity,
        string $metric
    ) {
        $this->setGranularity($granularity)
            ->setMetric($metric);
    }

    /**
     * @return string
     */
    public function getGranularity(): string
    {
        return $this->granularity;
    }

    /**
     * @param string $granularity
     * @return EnabledMetric
     */
    public function setGranularity(string $granularity): EnabledMetric
    {
        $this->granularity = $granularity;
        return $this;
    }

    /**
     * @return string
     */
    public function getMetric(): string
    {
        return $this->metric;
    }

    /**
     * @param string $metric
     * @return EnabledMetric
     */
    public function setMetric(string $metric): EnabledMetric
    {
        $this->metric = $metric;
        return $this;
    }
}
