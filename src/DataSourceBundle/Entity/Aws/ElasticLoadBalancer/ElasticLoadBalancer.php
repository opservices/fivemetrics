<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 04/02/17
 * Time: 15:04
 */

namespace DataSourceBundle\Entity\Aws\ElasticLoadBalancer;

use DataSourceBundle\Collection\Aws\ElasticLoadBalancer\BackendServerDescriptionCollection;
use DataSourceBundle\Collection\Aws\ElasticLoadBalancer\InstanceCollection;
use DataSourceBundle\Collection\Aws\ElasticLoadBalancer\InstanceHealthCollection;
use DataSourceBundle\Collection\Aws\ElasticLoadBalancer\ListenerDescription\ListenerDescriptionCollection;
use DataSourceBundle\Entity\Aws\EntityAbstract;
use DataSourceBundle\Entity\Aws\ElasticLoadBalancer\Policies\Policies;
use EssentialsBundle\Entity\DateTime\DateTime;

/**
 * Class ElasticLoadBalancer
 * @package Entity\Aws\ElasticLoadBalancer
 */
class ElasticLoadBalancer extends EntityAbstract
{
    /**
     * @var string
     */
    protected $loadBalancerName;

    /**
     * @var string
     */
    protected $DNSName;

    /**
     * @var string
     */
    protected $canonicalHostedZoneName;

    /**
     * var string
     */
    protected $canonicalHostedZoneNameID;

    /**
     * @var ListenerDescriptionCollection
     */
    protected $listenerDescriptions;

    /**
     * @var Policies
     */
    protected $policies;

    /**
     * @var BackendServerDescriptionCollection
     */
    protected $backendServerDescriptions;
    /**
     * @var array
     */
    protected $availabilityZones;

    /**
     * @var array
     */
    protected $subnets;

    /**
     * @var string
     */
    protected $VPCId;

    /**
     * @var InstanceCollection
     */
    protected $instances;

    /**
     * @var HealthCheck
     */
    protected $healthCheck;

    /**
     * @var SourceSecurityGroup
     */
    protected $sourceSecurityGroup;

    /**
     * @var array
     */
    protected $securityGroups;

    /**
     * @var DateTime
     */
    protected $createdTime;

    /**
     * @var string
     */
    protected $scheme;

    /**
     * @var InstanceHealthCollection
     */
    protected $instanceHealth;

    /**
     * ElasticLoadBalancer constructor.
     * @param string $loadBalancerName
     * @param string $DNSName
     * @param string $canonicalHostedZoneNameID
     * @param ListenerDescriptionCollection $listenerDescriptions
     * @param Policies $policies
     * @param BackendServerDescriptionCollection $backendServerDescriptions
     * @param array $availabilityZones
     * @param array $subnets
     * @param string $VPCId
     * @param InstanceCollection $instances
     * @param HealthCheck $healthCheck
     * @param SourceSecurityGroup $sourceSecurityGroup
     * @param array $securityGroups
     * @param DateTime $createdTime
     * @param string $scheme
     * @param null $canonicalHostedZoneName
     * @param InstanceHealthCollection|null $instanceHealth
     */
    public function __construct(
        string $loadBalancerName,
        string $DNSName,
        string $canonicalHostedZoneNameID,
        ListenerDescriptionCollection $listenerDescriptions,
        Policies $policies,
        BackendServerDescriptionCollection $backendServerDescriptions,
        array $availabilityZones,
        array $subnets,
        InstanceCollection $instances,
        HealthCheck $healthCheck,
        SourceSecurityGroup $sourceSecurityGroup,
        array $securityGroups,
        DateTime $createdTime,
        string $scheme,
        string $VPCId = null,
        $canonicalHostedZoneName = null,
        InstanceHealthCollection $instanceHealth = null
    ) {
        $this->setLoadBalancerName($loadBalancerName)
            ->setDNSName($DNSName)
            ->setCanonicalHostedZoneNameID($canonicalHostedZoneNameID)
            ->setListenerDescriptions($listenerDescriptions)
            ->setPolicies($policies)
            ->setBackendServerDescriptions($backendServerDescriptions)
            ->setAvailabilityZones($availabilityZones)
            ->setSubnets($subnets)
            ->setInstances($instances)
            ->setHealthCheck($healthCheck)
            ->setSourceSecurityGroup($sourceSecurityGroup)
            ->setSecurityGroups($securityGroups)
            ->setCreatedTime($createdTime)
            ->setScheme($scheme);

        (is_null($VPCId)) ?: $this->setVPCId($VPCId);
        (is_null($instanceHealth)) ?: $this->setInstanceHealth($instanceHealth);
        (is_null($canonicalHostedZoneName)) ?: $this->setCanonicalHostedZoneName($canonicalHostedZoneName);
    }

    /**
     * @return InstanceHealthCollection|null
     */
    public function getInstanceHealth()
    {
        return $this->instanceHealth;
    }

    /**
     * @param InstanceHealthCollection $instanceHealth
     * @return ElasticLoadBalancer
     */
    public function setInstanceHealth(
        InstanceHealthCollection $instanceHealth
    ): ElasticLoadBalancer {
        $this->instanceHealth = $instanceHealth;
        return $this;
    }

    /**
     * @return string
     */
    public function getLoadBalancerName(): string
    {
        return $this->loadBalancerName;
    }

    /**
     * @param string $loadBalancerName
     * @return ElasticLoadBalancer
     */
    public function setLoadBalancerName(string $loadBalancerName): ElasticLoadBalancer
    {
        $this->loadBalancerName = $loadBalancerName;
        return $this;
    }

    /**
     * @return string
     */
    public function getDNSName(): string
    {
        return $this->DNSName;
    }

    /**
     * @param string $DNSName
     * @return ElasticLoadBalancer
     */
    public function setDNSName(string $DNSName): ElasticLoadBalancer
    {
        $this->DNSName = $DNSName;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCanonicalHostedZoneName()
    {
        return $this->canonicalHostedZoneName;
    }

    /**
     * @param string $canonicalHostedZoneName
     * @return ElasticLoadBalancer
     */
    public function setCanonicalHostedZoneName(string $canonicalHostedZoneName): ElasticLoadBalancer
    {
        $this->canonicalHostedZoneName = $canonicalHostedZoneName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCanonicalHostedZoneNameID()
    {
        return $this->canonicalHostedZoneNameID;
    }

    /**
     * @param mixed $canonicalHostedZoneNameID
     * @return ElasticLoadBalancer
     */
    public function setCanonicalHostedZoneNameID($canonicalHostedZoneNameID): ElasticLoadBalancer
    {
        $this->canonicalHostedZoneNameID = $canonicalHostedZoneNameID;
        return $this;
    }

    /**
     * @return ListenerDescriptionCollection
     */
    public function getListenerDescriptions(): ListenerDescriptionCollection
    {
        return $this->listenerDescriptions;
    }

    /**
     * @param ListenerDescriptionCollection $listenerDescriptions
     * @return ElasticLoadBalancer
     */
    public function setListenerDescriptions(ListenerDescriptionCollection $listenerDescriptions): ElasticLoadBalancer
    {
        $this->listenerDescriptions = $listenerDescriptions;
        return $this;
    }

    /**
     * @return Policies
     */
    public function getPolicies(): Policies
    {
        return $this->policies;
    }

    /**
     * @param Policies $policies
     * @return ElasticLoadBalancer
     */
    public function setPolicies(Policies $policies): ElasticLoadBalancer
    {
        $this->policies = $policies;
        return $this;
    }

    /**
     * @return BackendServerDescriptionCollection
     */
    public function getBackendServerDescriptions(): BackendServerDescriptionCollection
    {
        return $this->backendServerDescriptions;
    }

    /**
     * @param BackendServerDescriptionCollection $backendServerDescriptions
     * @return ElasticLoadBalancer
     */
    public function setBackendServerDescriptions(
        BackendServerDescriptionCollection $backendServerDescriptions
    ): ElasticLoadBalancer {
        $this->backendServerDescriptions = $backendServerDescriptions;
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
     * @return ElasticLoadBalancer
     */
    public function setAvailabilityZones(array $availabilityZones): ElasticLoadBalancer
    {
        $this->availabilityZones = $availabilityZones;
        return $this;
    }

    /**
     * @return array
     */
    public function getSubnets(): array
    {
        return $this->subnets;
    }

    /**
     * @param array $subnets
     * @return ElasticLoadBalancer
     */
    public function setSubnets(array $subnets): ElasticLoadBalancer
    {
        $this->subnets = $subnets;
        return $this;
    }

    /**
     * @return string
     */
    public function getVPCId(): string
    {
        return $this->VPCId;
    }

    /**
     * @param string $VPCId
     * @return ElasticLoadBalancer
     */
    public function setVPCId(string $VPCId): ElasticLoadBalancer
    {
        $this->VPCId = $VPCId;
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
     * @return ElasticLoadBalancer
     */
    public function setInstances(InstanceCollection $instances): ElasticLoadBalancer
    {
        $this->instances = $instances;
        return $this;
    }

    /**
     * @return HealthCheck
     */
    public function getHealthCheck(): HealthCheck
    {
        return $this->healthCheck;
    }

    /**
     * @param HealthCheck $healthCheck
     * @return ElasticLoadBalancer
     */
    public function setHealthCheck(HealthCheck $healthCheck): ElasticLoadBalancer
    {
        $this->healthCheck = $healthCheck;
        return $this;
    }

    /**
     * @return SourceSecurityGroup
     */
    public function getSourceSecurityGroup(): SourceSecurityGroup
    {
        return $this->sourceSecurityGroup;
    }

    /**
     * @param SourceSecurityGroup $sourceSecurityGroup
     * @return ElasticLoadBalancer
     */
    public function setSourceSecurityGroup(SourceSecurityGroup $sourceSecurityGroup): ElasticLoadBalancer
    {
        $this->sourceSecurityGroup = $sourceSecurityGroup;
        return $this;
    }

    /**
     * @return array
     */
    public function getSecurityGroups(): array
    {
        return $this->securityGroups;
    }

    /**
     * @param array $securityGroups
     * @return ElasticLoadBalancer
     */
    public function setSecurityGroups(array $securityGroups): ElasticLoadBalancer
    {
        $this->securityGroups = $securityGroups;
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
     * @return ElasticLoadBalancer
     */
    public function setCreatedTime(DateTime $createdTime): ElasticLoadBalancer
    {
        $this->createdTime = $createdTime;
        return $this;
    }

    /**
     * @return string
     */
    public function getScheme(): string
    {
        return $this->scheme;
    }

    /**
     * @param string $scheme
     * @return ElasticLoadBalancer
     */
    public function setScheme(string $scheme): ElasticLoadBalancer
    {
        $this->scheme = $scheme;
        return $this;
    }
}
