<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 05/02/17
 * Time: 10:23
 */

namespace DataSourceBundle\Entity\Aws\ElasticLoadBalancer;

/**
 * Class InstanceHealth
 * @package DataSourceBundle\Entity\Aws\ElasticLoadBalancer
 */
class InstanceHealth extends Instance
{
    /**
     * @var string
     */
    protected $state;

    /**
     * @var string
     */
    protected $reasonCode;

    /**
     * @var string
     */
    protected $description;

    /**
     * InstanceHealth constructor.
     * @param string $instanceId
     * @param string $state
     * @param string $reasonCode
     * @param string $description
     */
    public function __construct(
        string $instanceId,
        string $state,
        string $reasonCode,
        string $description
    ) {
        parent::__construct($instanceId);
        $this->setState($state)
            ->setReasonCode($reasonCode)
            ->setDescription($description);
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @param string $state
     * @return InstanceHealth
     */
    public function setState(string $state): InstanceHealth
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @return string
     */
    public function getReasonCode(): string
    {
        return $this->reasonCode;
    }

    /**
     * @param string $reasonCode
     * @return InstanceHealth
     */
    public function setReasonCode(string $reasonCode): InstanceHealth
    {
        $this->reasonCode = $reasonCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return InstanceHealth
     */
    public function setDescription(string $description): InstanceHealth
    {
        $this->description = $description;
        return $this;
    }
}
