<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 03/02/17
 * Time: 08:45
 */

namespace DataSourceBundle\Entity\Aws\AutoScaling;

use DataSourceBundle\Collection\Aws\AutoScaling\ActivityCollection;
use DataSourceBundle\Collection\Aws\AutoScaling\EnabledMetricCollection;
use DataSourceBundle\Collection\Aws\AutoScaling\InstanceCollection;
use DataSourceBundle\Collection\Aws\AutoScaling\SuspendedProcessCollection;
use DataSourceBundle\Collection\Aws\AutoScaling\TagCollection;
use DataSourceBundle\Entity\Aws\EntityAbstract;
use EssentialsBundle\Entity\DateTime\DateTime;

/**
 * Class AutoScalingGroup
 * @package DataSourceBundle\Entity\Aws\AutoScaling
 */
class AutoScalingGroup extends EntityAbstract
{
    /**
     * @var string
     */
    protected $autoScalingGroupName;

    /**
     * @var string
     */
    protected $autoScalingGroupARN;

    /**
     * @var int
     */
    protected $minSize;

    /**
     * @var int
     */
    protected $maxSize;

    /**
     * @var int
     */
    protected $desiredCapacity;

    /**
     * @var int
     */
    protected $defaultCooldown;

    /**
     * @var string
     */
    protected $healthCheckType;

    /**
     * @var int
     */
    protected $healthCheckGracePeriod;

    /**
     * @var DateTime
     */
    protected $createdTime;

    /**
     * @var string
     */
    protected $VPCZoneIdentifier;

    /**
     * @var bool
     */
    protected $newInstancesProtectedFromScaleIn;

    /**
     * @var array
     */
    protected $terminationPolicies = [];

    /**
     * @var array
     */
    protected $availabilityZones = [];

    /**
     * @var array
     */
    protected $loadBalancerNames = [];

    /**
     * @var array
     */
    protected $targetGroupARNs = [];

    /**
     * @var string
     */
    protected $launchConfigurationName;

    /**
     * @var InstanceCollection
     */
    protected $instances;

    /**
     * @var SuspendedProcessCollection
     */
    protected $suspendedProcesses;

    /**
     * @var EnabledMetricCollection
     */
    protected $enabledMetrics;

    /**
     * @var TagCollection
     */
    protected $tags;

    /**
     * @var ActivityCollection
     */
    protected $activities;

    /**
     * AutoScalingGroup constructor.
     * @param string $autoScalingGroupName
     * @param string $autoScalingGroupARN
     * @param string $launchConfigurationName
     * @param int $minSize
     * @param int $maxSize
     * @param int $desiredCapacity
     * @param int $defaultCooldown
     * @param array $availabilityZones
     * @param array $loadBalancerNames
     * @param array $targetGroupARNs
     * @param string $healthCheckType
     * @param int $healthCheckGracePeriod
     * @param DateTime $createdTime
     * @param string $VPCZoneIdentifier
     * @param array $terminationPolicies
     * @param bool $newInstancesProtectedFromScaleIn
     * @param InstanceCollection $instances
     * @param SuspendedProcessCollection $suspendedProcesses
     * @param EnabledMetricCollection $enabledMetrics
     * @param TagCollection $tags
     * @param ActivityCollection $activityCollection
     */
    public function __construct(
        string $autoScalingGroupName,
        string $autoScalingGroupARN,
        string $launchConfigurationName,
        int $minSize,
        int $maxSize,
        int $desiredCapacity,
        int $defaultCooldown,
        array $availabilityZones,
        array $loadBalancerNames,
        array $targetGroupARNs,
        string $healthCheckType,
        int $healthCheckGracePeriod,
        DateTime $createdTime,
        string $VPCZoneIdentifier,
        array $terminationPolicies,
        bool $newInstancesProtectedFromScaleIn,
        InstanceCollection $instances,
        SuspendedProcessCollection $suspendedProcesses,
        EnabledMetricCollection $enabledMetrics,
        TagCollection $tags,
        ActivityCollection $activityCollection = null
    ) {
        $this->setAutoScalingGroupName($autoScalingGroupName)
            ->setAutoScalingGroupARN($autoScalingGroupARN)
            ->setLaunchConfigurationName($launchConfigurationName)
            ->setMinSize($minSize)
            ->setMaxSize($maxSize)
            ->setDesiredCapacity($desiredCapacity)
            ->setDefaultCooldown($defaultCooldown)
            ->setAvailabilityZones($availabilityZones)
            ->setLoadBalancerNames($loadBalancerNames)
            ->setTargetGroupARNs($targetGroupARNs)
            ->setHealthCheckType($healthCheckType)
            ->setHealthCheckGracePeriod($healthCheckGracePeriod)
            ->setCreatedTime($createdTime)
            ->setVPCZoneIdentifier($VPCZoneIdentifier)
            ->setTerminationPolicies($terminationPolicies)
            ->setNewInstancesProtectedFromScaleIn($newInstancesProtectedFromScaleIn)
            ->setInstances($instances)
            ->setSuspendedProcesses($suspendedProcesses)
            ->setEnabledMetrics($enabledMetrics)
            ->setTags($tags);

        if (! is_null($activityCollection)) {
            $this->setActivities($activityCollection);
        }
    }

    /**
     * @return ActivityCollection
     */
    public function getActivities(): ActivityCollection
    {
        return $this->activities;
    }

    /**
     * @param ActivityCollection $activities
     * @return AutoScalingGroup
     */
    public function setActivities(ActivityCollection $activities): AutoScalingGroup
    {
        $this->activities = $activities;
        return $this;
    }

    /**
     * @return TagCollection
     */
    public function getTags(): TagCollection
    {
        return $this->tags;
    }

    /**
     * @param TagCollection $tags
     * @return AutoScalingGroup
     */
    public function setTags(TagCollection $tags): AutoScalingGroup
    {
        $this->tags = $tags;
        return $this;
    }

    /**
     * @return EnabledMetricCollection
     */
    public function getEnabledMetrics(): EnabledMetricCollection
    {
        return $this->enabledMetrics;
    }

    /**
     * @param EnabledMetricCollection $enabledMetrics
     * @return AutoScalingGroup
     */
    public function setEnabledMetrics(EnabledMetricCollection $enabledMetrics): AutoScalingGroup
    {
        $this->enabledMetrics = $enabledMetrics;
        return $this;
    }

    /**
     * @return SuspendedProcessCollection
     */
    public function getSuspendedProcesses(): SuspendedProcessCollection
    {
        return $this->suspendedProcesses;
    }

    /**
     * @param SuspendedProcessCollection $suspendedProcesses
     * @return AutoScalingGroup
     */
    public function setSuspendedProcesses(SuspendedProcessCollection $suspendedProcesses): AutoScalingGroup
    {
        $this->suspendedProcesses = $suspendedProcesses;
        return $this;
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
     * @return AutoScalingGroup
     */
    public function setInstances(InstanceCollection $instances): AutoScalingGroup
    {
        $this->instances = $instances;
        return $this;
    }

    /**
     * @return array
     */
    public function getTerminationPolicies(): array
    {
        return $this->terminationPolicies;
    }

    /**
     * @param array $terminationPolicies
     * @return AutoScalingGroup
     */
    public function setTerminationPolicies(array $terminationPolicies): AutoScalingGroup
    {
        $this->terminationPolicies = $terminationPolicies;
        return $this;
    }

    /**
     * @return array
     */
    public function getAvailabilityZones(): array
    {
        return $this->availabilityZones;
    }

    /**
     * @param array $availabilityZones
     * @return AutoScalingGroup
     */
    public function setAvailabilityZones(array $availabilityZones): AutoScalingGroup
    {
        $this->availabilityZones = $availabilityZones;
        return $this;
    }

    /**
     * @return array
     */
    public function getLoadBalancerNames(): array
    {
        return $this->loadBalancerNames;
    }

    /**
     * @param array $loadBalancerNames
     * @return AutoScalingGroup
     */
    public function setLoadBalancerNames(array $loadBalancerNames): AutoScalingGroup
    {
        $this->loadBalancerNames = $loadBalancerNames;
        return $this;
    }

    /**
     * @return array
     */
    public function getTargetGroupARNs(): array
    {
        return $this->targetGroupARNs;
    }

    /**
     * @param array $targetGroupARNs
     * @return AutoScalingGroup
     */
    public function setTargetGroupARNs(array $targetGroupARNs): AutoScalingGroup
    {
        $this->targetGroupARNs = $targetGroupARNs;
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
     * @return AutoScalingGroup
     */
    public function setLaunchConfigurationName(string $launchConfigurationName): AutoScalingGroup
    {
        $this->launchConfigurationName = $launchConfigurationName;
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
     * @return AutoScalingGroup
     */
    public function setAutoScalingGroupName(string $autoScalingGroupName): AutoScalingGroup
    {
        $this->autoScalingGroupName = $autoScalingGroupName;
        return $this;
    }

    /**
     * @return string
     */
    public function getAutoScalingGroupARN(): string
    {
        return $this->autoScalingGroupARN;
    }

    /**
     * @param string $autoScalingGroupARN
     * @return AutoScalingGroup
     */
    public function setAutoScalingGroupARN(string $autoScalingGroupARN): AutoScalingGroup
    {
        $this->autoScalingGroupARN = $autoScalingGroupARN;
        return $this;
    }

    /**
     * @return int
     */
    public function getMinSize(): int
    {
        return $this->minSize;
    }

    /**
     * @param int $minSize
     * @return AutoScalingGroup
     */
    public function setMinSize(int $minSize): AutoScalingGroup
    {
        $this->minSize = $minSize;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxSize(): int
    {
        return $this->maxSize;
    }

    /**
     * @param int $maxSize
     * @return AutoScalingGroup
     */
    public function setMaxSize(int $maxSize): AutoScalingGroup
    {
        $this->maxSize = $maxSize;
        return $this;
    }

    /**
     * @return int
     */
    public function getDesiredCapacity(): int
    {
        return $this->desiredCapacity;
    }

    /**
     * @param int $desiredCapacity
     * @return AutoScalingGroup
     */
    public function setDesiredCapacity(int $desiredCapacity): AutoScalingGroup
    {
        $this->desiredCapacity = $desiredCapacity;
        return $this;
    }

    /**
     * @return int
     */
    public function getDefaultCooldown(): int
    {
        return $this->defaultCooldown;
    }

    /**
     * @param int $defaultCooldown
     * @return AutoScalingGroup
     */
    public function setDefaultCooldown(int $defaultCooldown): AutoScalingGroup
    {
        $this->defaultCooldown = $defaultCooldown;
        return $this;
    }

    /**
     * @return string
     */
    public function getHealthCheckType(): string
    {
        return $this->healthCheckType;
    }

    /**
     * @param string $healthCheckType
     * @return AutoScalingGroup
     */
    public function setHealthCheckType(string $healthCheckType): AutoScalingGroup
    {
        $this->healthCheckType = $healthCheckType;
        return $this;
    }

    /**
     * @return int
     */
    public function getHealthCheckGracePeriod(): int
    {
        return $this->healthCheckGracePeriod;
    }

    /**
     * @param int $healthCheckGracePeriod
     * @return AutoScalingGroup
     */
    public function setHealthCheckGracePeriod(int $healthCheckGracePeriod): AutoScalingGroup
    {
        $this->healthCheckGracePeriod = $healthCheckGracePeriod;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreatedTime(): DateTime
    {
        return $this->createdTime;
    }

    /**
     * @param DateTime $createdTime
     * @return AutoScalingGroup
     */
    public function setCreatedTime(DateTime $createdTime): AutoScalingGroup
    {
        $this->createdTime = $createdTime;
        return $this;
    }

    /**
     * @return string
     */
    public function getVPCZoneIdentifier(): string
    {
        return $this->VPCZoneIdentifier;
    }

    /**
     * @param string $VPCZoneIdentifier
     * @return AutoScalingGroup
     */
    public function setVPCZoneIdentifier(string $VPCZoneIdentifier): AutoScalingGroup
    {
        $this->VPCZoneIdentifier = $VPCZoneIdentifier;
        return $this;
    }

    /**
     * @return bool
     */
    public function isNewInstancesProtectedFromScaleIn(): bool
    {
        return $this->newInstancesProtectedFromScaleIn;
    }

    /**
     * @param bool $newInstancesProtectedFromScaleIn
     * @return AutoScalingGroup
     */
    public function setNewInstancesProtectedFromScaleIn(bool $newInstancesProtectedFromScaleIn): AutoScalingGroup
    {
        $this->newInstancesProtectedFromScaleIn = $newInstancesProtectedFromScaleIn;
        return $this;
    }
}
