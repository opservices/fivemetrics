<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 03/02/17
 * Time: 09:55
 */

namespace DataSourceBundle\Entity\Aws\AutoScaling;

use DataSourceBundle\Entity\Aws\EntityAbstract;

/**
 * Class Instance
 * @package DataSourceBundle\Entity\Aws\AutoScaling
 */
class Instance extends EntityAbstract
{
    const INSTANCE_LIFECYCLE_STATE_NAMES = [
        'Pending',
        'Pending:Wait',
        'Pending:Proceed',
        'Quarantined',
        'InService',
        'Terminating',
        'Terminating:Wait',
        'Terminating:Proceed',
        'Terminated',
        'Detaching',
        'Detached',
        'EnteringStandby',
        'Standby'
    ];

    /**
     * @var string
     */
    protected $instanceId;

    /**
     * @var string
     */
    protected $availabilityZone;

    /**
     * @var string
     */
    protected $lifecycleState;

    /**
     * @var string
     */
    protected $healthStatus;

    /**
     * @var string
     */
    protected $launchConfigurationName;

    /**
     * @var bool
     */
    protected $protectedFromScaleIn;

    /**
     * @var string
     */
    protected $autoScalingGroupName;

    /**
     * Instance constructor.
     * @param string $instanceId
     * @param string $availabilityZone
     * @param string $lifecycleState
     * @param string $healthStatus
     * @param string $launchConfigurationName
     * @param bool $protectedFromScaleIn
     * @param string $autoScalingGroupName
     */
    public function __construct(
        string $instanceId,
        string $availabilityZone,
        string $lifecycleState,
        string $healthStatus,
        string $launchConfigurationName,
        bool $protectedFromScaleIn,
        string $autoScalingGroupName
    ) {
        $this->setAutoScalingGroupName($autoScalingGroupName)
            ->setAvailabilityZone($availabilityZone)
            ->setHealthStatus($healthStatus)
            ->setInstanceId($instanceId)
            ->setLaunchConfigurationName($launchConfigurationName)
            ->setLifecycleState($lifecycleState)
            ->setProtectedFromScaleIn($protectedFromScaleIn);
    }

    /**
     * @return string
     */
    public function getInstanceId(): string
    {
        return $this->instanceId;
    }

    /**
     * @param string $instanceId
     * @return Instance
     */
    public function setInstanceId(string $instanceId): Instance
    {
        $this->instanceId = $instanceId;
        return $this;
    }

    /**
     * @return string
     */
    public function getAvailabilityZone(): string
    {
        return (string) $this->availabilityZone;
    }

    /**
     * @param string $availabilityZone
     * @return Instance
     */
    public function setAvailabilityZone(string $availabilityZone): Instance
    {
        $this->availabilityZone = $availabilityZone;
        return $this;
    }

    /**
     * @return string
     */
    public function getLifecycleState(): string
    {
        return $this->lifecycleState;
    }

    /**
     * @param string $lifecycleState
     * @return Instance
     */
    public function setLifecycleState(string $lifecycleState): Instance
    {
        if (! in_array($lifecycleState, self::INSTANCE_LIFECYCLE_STATE_NAMES)) {
            throw new \InvalidArgumentException(
                'An invalid lifecycle state name was provided:'
                . ' "' . $lifecycleState . '"'
            );
        }

        $this->lifecycleState = $lifecycleState;
        return $this;
    }

    /**
     * @return string
     */
    public function getHealthStatus(): string
    {
        return $this->healthStatus;
    }

    /**
     * @param string $healthStatus
     * @return Instance
     */
    public function setHealthStatus(string $healthStatus): Instance
    {
        $this->healthStatus = $healthStatus;
        return $this;
    }

    /**
     * @return string
     */
    public function getLaunchConfigurationName(): string
    {
        return $this->launchConfigurationName;
    }

    /**
     * @param string $launchConfigurationName
     * @return Instance
     */
    public function setLaunchConfigurationName(string $launchConfigurationName): Instance
    {
        $this->launchConfigurationName = $launchConfigurationName;
        return $this;
    }

    /**
     * @return bool
     */
    public function isProtectedFromScaleIn(): bool
    {
        return $this->protectedFromScaleIn;
    }

    /**
     * @param bool $protectedFromScaleIn
     * @return Instance
     */
    public function setProtectedFromScaleIn(bool $protectedFromScaleIn): Instance
    {
        $this->protectedFromScaleIn = $protectedFromScaleIn;
        return $this;
    }

    /**
     * @return string
     */
    public function getAutoScalingGroupName(): string
    {
        return $this->autoScalingGroupName;
    }

    /**
     * @param string $autoScalingGroupName
     * @return Instance
     */
    public function setAutoScalingGroupName(string $autoScalingGroupName): Instance
    {
        $this->autoScalingGroupName = $autoScalingGroupName;
        return $this;
    }
}
