<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 04/02/17
 * Time: 15:20
 */

namespace DataSourceBundle\Entity\Aws\ElasticLoadBalancer;

use DataSourceBundle\Entity\Aws\EntityAbstract;

/**
 * Class HealthCheck
 * @package DataSourceBundle\Entity\Aws\ElasticLoadBalancer
 */
class HealthCheck extends EntityAbstract
{
    /**
     * @var string
     */
    protected $target;

    /**
     * @var int
     */
    protected $interval;
    /**
     * @var int
     */
    protected $timeout;

    /**
     * @var int
     */
    protected $unhealthyThreshold;

    /**
     * @var int
     */
    protected $healthyThreshold;

    /**
     * HealthCheck constructor.
     * @param string $target
     * @param int $interval
     * @param int $timeout
     * @param int $unhealthyThreshold
     * @param int $healthyThreshold
     */
    public function __construct(
        string $target,
        int $interval,
        int $timeout,
        int $unhealthyThreshold,
        int $healthyThreshold
    ) {
        $this->setTarget($target)
            ->setInterval($interval)
            ->setTimeout($timeout)
            ->setUnhealthyThreshold($unhealthyThreshold)
            ->setHealthyThreshold($healthyThreshold);
    }

    /**
     * @return string
     */
    public function getTarget(): string
    {
        return $this->target;
    }

    /**
     * @param string $target
     * @return HealthCheck
     */
    public function setTarget(string $target): HealthCheck
    {
        $this->target = $target;
        return $this;
    }

    /**
     * @return int
     */
    public function getInterval(): int
    {
        return $this->interval;
    }

    /**
     * @param int $interval
     * @return HealthCheck
     */
    public function setInterval(int $interval): HealthCheck
    {
        $this->interval = $interval;
        return $this;
    }

    /**
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * @param int $timeout
     * @return HealthCheck
     */
    public function setTimeout(int $timeout): HealthCheck
    {
        $this->timeout = $timeout;
        return $this;
    }

    /**
     * @return int
     */
    public function getUnhealthyThreshold(): int
    {
        return $this->unhealthyThreshold;
    }

    /**
     * @param int $unhealthyThreshold
     * @return HealthCheck
     */
    public function setUnhealthyThreshold(int $unhealthyThreshold): HealthCheck
    {
        $this->unhealthyThreshold = $unhealthyThreshold;
        return $this;
    }

    /**
     * @return int
     */
    public function getHealthyThreshold(): int
    {
        return $this->healthyThreshold;
    }

    /**
     * @param int $healthyThreshold
     * @return HealthCheck
     */
    public function setHealthyThreshold(int $healthyThreshold): HealthCheck
    {
        $this->healthyThreshold = $healthyThreshold;
        return $this;
    }
}
