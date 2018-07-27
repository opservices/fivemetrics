<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 20/01/17
 * Time: 10:45
 */

namespace DataSourceBundle\Entity\Aws\EC2\Instance;

use DataSourceBundle\Collection\Aws\EC2\BlockDeviceMappingCollection;
use DataSourceBundle\Collection\Aws\EC2\NetworkInterfaceCollection;
use DataSourceBundle\Collection\Aws\EC2\ProductCodeCollection;
use DataSourceBundle\Collection\Aws\EC2\SecurityGroupCollection;
use DataSourceBundle\Collection\Aws\Tag\TagCollection;
use DataSourceBundle\Entity\Aws\EntityAbstract;
use EssentialsBundle\Entity\DateTime\DateTime;

/**
 * Class Instance
 * @package DataSourceBundle\Entity\Aws\EC2\Instance
 */
class Instance extends EntityAbstract
{
    const ROOT_DEVICE_TYPES = [
        'ebs',
        'instance-store'
    ];

    const VIRTUALIZATION_TYPES = [
        'hvm',
        'paravirtual'
    ];

    const HYPERVISOR_TYPES = [
        'ovm',
        'xen'
    ];

    const ARCHITECTURE_TYPES = [
        'i386',
        'x86_64'
    ];

    /**
     * @var string
     */
    protected $instanceId;

    /**
     * @var string
     */
    protected $imageId;

    /**
     * @var InstanceState
     */
    protected $state;

    /**
     * @var string
     */
    protected $privateDnsName;

    /**
     * @var string
     */
    protected $publicDnsName;

    /**
     * @var string
     */
    protected $stateTransitionReason;

    /**
     * @var string
     */
    protected $keyName;

    /**
     * @var int
     */
    protected $amiLaunchIndex;

    /**
     * @var ProductCodeCollection
     */
    protected $productCodes;

    /**
     * @var string
     */
    protected $instanceType;

    /**
     * @var DateTime
     */
    protected $launchTime;

    /**
     * @var Placement
     */
    protected $placement;

    /**
     * @var Monitoring
     */
    protected $monitoring;

    /**
     * @var string
     */
    protected $subnetId;

    /**
     * @var string
     */
    protected $vpcId;

    /**
     * @var string
     */
    protected $privateIpAddress;

    /**
     * @var StateReason
     */
    protected $stateReason;

    /**
     * @var string
     */
    protected $architecture;

    /**
     * @var string
     */
    protected $rootDeviceType;

    /**
     * @var string
     */
    protected $rootDeviceName;

    /**
     * @var BlockDeviceMappingCollection
     */
    protected $blockDeviceMappings;

    /**
     * @var string
     */
    protected $virtualizationType;

    /**
     * @var string
     */
    protected $clientToken;

    /**
     * @var TagCollection
     */
    protected $tags;

    /**
     * @var SecurityGroupCollection
     */
    protected $securityGroups;

    /**
     * @var bool
     */
    protected $sourceDestCheck;

    /**
     * @var string
     */
    protected $hypervisor;

    /**
     * @var bool
     */
    protected $ebsOptimized;

    /**
     * @var NetworkInterfaceCollection
     */
    protected $networkInterfaces;

    /**
     * @var bool
     */
    protected $enaSupport;
    /**
     * @var string
     */
    protected $instanceLifecycle;

    /**
     * @var IamInstanceProfile
     */
    protected $iamInstanceProfile;

    /**
     * @var string
     */
    protected $kernelId;

    /**
     * @var string
     */
    protected $platform;

    /**
     * @var string
     */
    protected $publicIpAddress;

    /**
     * @var string
     */
    protected $ramdiskId;

    /**
     * @var string
     */
    protected $sriovNetSupport;

    /**
     * @var string
     */
    protected $spotInstanceRequestId;

    /**
     * Instance constructor.
     * @param string $instanceId
     * @param string $imageId
     * @param string $privateDnsName
     * @param string $publicDnsName
     * @param string $stateTransitionReason
     * @param int $amiLaunchIndex
     * @param string $instanceType
     * @param string $architecture
     * @param string $rootDeviceType
     * @param string $rootDeviceName
     * @param string $virtualizationType
     * @param string $clientToken
     * @param string $hypervisor
     * @param bool $ebsOptimized
     * @param DateTime $launchTime
     * @param Monitoring $monitoring
     * @param Placement $placement
     * @param InstanceState $state
     * @param TagCollection $tags
     * @param ProductCodeCollection $productCodes
     * @param BlockDeviceMappingCollection $blockDeviceMappings
     * @param SecurityGroupCollection $securityGroups
     * @param NetworkInterfaceCollection $networkInterfaces
     * @param null $sriovNetSupport
     * @param null $spotInstanceRequestId
     * @param null $ramdiskId
     * @param null $publicIpAddress
     * @param null $platform
     * @param null $kernelId
     * @param null $instanceLifecycle
     * @param bool $enaSupport
     * @param null $keyName
     * @param null $subnetId
     * @param null $vpcId
     * @param bool|null $sourceDestCheck
     * @param null $privateIpAddress
     * @param IamInstanceProfile|null $iamInstanceProfile
     * @param StateReason|null $stateReason
     */
    public function __construct(
        string $instanceId,
        string $imageId,
        string $privateDnsName,
        string $publicDnsName,
        string $stateTransitionReason,
        int $amiLaunchIndex,
        string $instanceType,
        string $architecture,
        string $rootDeviceType,
        string $rootDeviceName,
        string $virtualizationType,
        string $clientToken,
        string $hypervisor,
        bool $ebsOptimized,
        DateTime $launchTime,
        Monitoring $monitoring,
        Placement $placement,
        InstanceState $state,
        TagCollection $tags,
        ProductCodeCollection $productCodes,
        BlockDeviceMappingCollection $blockDeviceMappings,
        SecurityGroupCollection $securityGroups,
        NetworkInterfaceCollection $networkInterfaces,
        $sriovNetSupport = null,
        $spotInstanceRequestId = null,
        $ramdiskId = null,
        $publicIpAddress = null,
        $platform = null,
        $kernelId = null,
        $instanceLifecycle = null,
        bool $enaSupport = false,
        $keyName = null,
        $subnetId = null,
        $vpcId = null,
        bool $sourceDestCheck = null,
        $privateIpAddress = null,
        IamInstanceProfile $iamInstanceProfile = null,
        StateReason $stateReason = null
    ) {
        $this->setInstanceId($instanceId)
            ->setImageId($imageId)
            ->setState($state)
            ->setPrivateDnsName($privateDnsName)
            ->setPublicDnsName($publicDnsName)
            ->setStateTransitionReason($stateTransitionReason)
            ->setAmiLaunchIndex($amiLaunchIndex)
            ->setProductCodes($productCodes)
            ->setInstanceType($instanceType)
            ->setLaunchTime($launchTime)
            ->setPlacement($placement)
            ->setMonitoringState($monitoring)
            ->setArchitecture($architecture)
            ->setRootDeviceType($rootDeviceType)
            ->setRootDeviceName($rootDeviceName)
            ->setBlockDeviceMappings($blockDeviceMappings)
            ->setVirtualizationType($virtualizationType)
            ->setClientToken($clientToken)
            ->setTags($tags)
            ->setSecurityGroups($securityGroups)
            ->setHypervisor($hypervisor)
            ->setEbsOptimized($ebsOptimized)
            ->setNetworkInterfaces($networkInterfaces)
            ->setEnaSupport($enaSupport)
            ->setPlatform((empty($platform)) ? 'Linux/Unix' : $platform);

        (empty($stateReason)) ?: $this->setStateReason($stateReason);
        (empty($subnetId)) ?: $this->setSubnetId($subnetId);
        (empty($vpcId)) ?: $this->setVpcId($vpcId);
        (empty($sourceDestCheck)) ?: $this->setSourceDestCheck($sourceDestCheck);
        (empty($privateIpAddress)) ?: $this->setPrivateIpAddress($privateIpAddress);
        (empty($keyName)) ?: $this->setKeyName($keyName);
        (empty($iamInstanceProfile)) ?: $this->setIamInstanceProfile($iamInstanceProfile);
        (empty($instanceLifecycle)) ?: $this->setInstanceLifecycle($instanceLifecycle);
        (empty($kernelId)) ?: $this->setKernelId($kernelId);
        (empty($publicIpAddress)) ?: $this->setPublicIpAddress($publicIpAddress);
        (empty($ramdiskId)) ?: $this->setRamdiskId($ramdiskId);
        (empty($spotInstanceRequestId)) ?: $this->setSpotInstanceRequestId($spotInstanceRequestId);
        (empty($sriovNetSupport)) ?: $this->setSriovNetSupport($sriovNetSupport);
    }

    /**
     * @return string
     */
    public function getSriovNetSupport(): string
    {
        return $this->sriovNetSupport;
    }

    /**
     * @param string $sriovNetSupport
     * @return Instance
     */
    public function setSriovNetSupport(string $sriovNetSupport): Instance
    {
        $this->sriovNetSupport = $sriovNetSupport;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSpotInstanceRequestId()
    {
        return $this->spotInstanceRequestId;
    }

    /**
     * @param string $spotInstanceRequestId
     * @return Instance
     */
    public function setSpotInstanceRequestId(string $spotInstanceRequestId): Instance
    {
        $this->spotInstanceRequestId = $spotInstanceRequestId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRamdiskId()
    {
        return $this->ramdiskId;
    }

    /**
     * @param string $ramdiskId
     * @return Instance
     */
    public function setRamdiskId(string $ramdiskId): Instance
    {
        $this->ramdiskId = $ramdiskId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPublicIpAddress()
    {
        return $this->publicIpAddress;
    }

    /**
     * @param string $publicIpAddress
     * @return Instance
     */
    public function setPublicIpAddress(string $publicIpAddress): Instance
    {
        $this->publicIpAddress = $publicIpAddress;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPlatform()
    {
        return $this->platform;
    }

    /**
     * @param mixed $platform
     * @return Instance
     */
    public function setPlatform(string $platform): Instance
    {
        $this->platform = $platform;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getKernelId()
    {
        return $this->kernelId;
    }

    /**
     * @param string $kernelId
     * @return Instance
     */
    public function setKernelId(string $kernelId): Instance
    {
        $this->kernelId = $kernelId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getInstanceLifecycle()
    {
        return $this->instanceLifecycle;
    }

    /**
     * @param string $instanceLifecycle
     * @return Instance
     */
    public function setInstanceLifecycle(string $instanceLifecycle): Instance
    {
        $this->instanceLifecycle = $instanceLifecycle;
        return $this;
    }

    /**
     * @return IamInstanceProfile|null
     */
    public function getIamInstanceProfile()
    {
        return $this->iamInstanceProfile;
    }

    /**
     * @param IamInstanceProfile $iamInstanceProfile
     * @return Instance
     */
    public function setIamInstanceProfile(IamInstanceProfile $iamInstanceProfile): Instance
    {
        $this->iamInstanceProfile = $iamInstanceProfile;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnaSupport(): bool
    {
        return $this->enaSupport;
    }

    /**
     * @param bool $enaSupport
     * @return Instance
     */
    public function setEnaSupport(bool $enaSupport): Instance
    {
        $this->enaSupport = $enaSupport;
        return $this;
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
    public function getImageId(): string
    {
        return $this->imageId;
    }

    /**
     * @param string $imageId
     * @return Instance
     */
    public function setImageId(string $imageId): Instance
    {
        $this->imageId = $imageId;
        return $this;
    }

    /**
     * @return InstanceState
     */
    public function getState(): InstanceState
    {
        return $this->state;
    }

    /**
     * @param InstanceState $state
     * @return Instance
     */
    public function setState(InstanceState $state): Instance
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrivateDnsName(): string
    {
        return $this->privateDnsName;
    }

    /**
     * @param string $privateDnsName
     * @return Instance
     */
    public function setPrivateDnsName(string $privateDnsName): Instance
    {
        $this->privateDnsName = $privateDnsName;
        return $this;
    }

    /**
     * @return string
     */
    public function getPublicDnsName(): string
    {
        return $this->publicDnsName;
    }

    /**
     * @param string $publicDnsName
     * @return Instance
     */
    public function setPublicDnsName(string $publicDnsName): Instance
    {
        $this->publicDnsName = $publicDnsName;
        return $this;
    }

    /**
     * @return string
     */
    public function getStateTransitionReason(): string
    {
        return $this->stateTransitionReason;
    }

    /**
     * @param string $stateTransitionReason
     * @return Instance
     */
    public function setStateTransitionReason(string $stateTransitionReason): Instance
    {
        $this->stateTransitionReason = $stateTransitionReason;
        return $this;
    }

    /**
     * @return string
     */
    public function getKeyName(): string
    {
        return $this->keyName;
    }

    /**
     * @param string $keyName
     * @return Instance
     */
    public function setKeyName(string $keyName): Instance
    {
        $this->keyName = $keyName;
        return $this;
    }

    /**
     * @return int
     */
    public function getAmiLaunchIndex(): int
    {
        return $this->amiLaunchIndex;
    }

    /**
     * @param int $amiLaunchIndex
     * @return Instance
     */
    public function setAmiLaunchIndex(int $amiLaunchIndex): Instance
    {
        $this->amiLaunchIndex = $amiLaunchIndex;
        return $this;
    }

    /**
     * @return ProductCodeCollection
     */
    public function getProductCodes(): ProductCodeCollection
    {
        return $this->productCodes;
    }

    /**
     * @param ProductCodeCollection $productCodes
     * @return Instance
     */
    public function setProductCodes(ProductCodeCollection $productCodes): Instance
    {
        $this->productCodes = $productCodes;
        return $this;
    }

    /**
     * @return string
     */
    public function getInstanceType(): string
    {
        return $this->instanceType;
    }

    /**
     * @param string $instanceType
     * @return Instance
     */
    public function setInstanceType(string $instanceType): Instance
    {
        $this->instanceType = $instanceType;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getLaunchTime(): DateTime
    {
        return $this->launchTime;
    }

    /**
     * @param DateTime $launchTime
     * @return Instance
     */
    public function setLaunchTime(DateTime $launchTime): Instance
    {
        $this->launchTime = $launchTime;
        return $this;
    }

    /**
     * @return Placement
     */
    public function getPlacement(): Placement
    {
        return $this->placement;
    }

    /**
     * @param Placement $placement
     * @return Instance
     */
    public function setPlacement(Placement $placement): Instance
    {
        $this->placement = $placement;
        return $this;
    }

    /**
     * @return Monitoring
     */
    public function getMonitoring(): Monitoring
    {
        return $this->monitoring;
    }

    /**
     * @param Monitoring $monitoring
     * @return Instance
     */
    public function setMonitoringState(Monitoring $monitoring): Instance
    {
        $this->monitoring = $monitoring;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubnetId()
    {
        return $this->subnetId;
    }

    /**
     * @param string $subnetId
     * @return Instance
     */
    public function setSubnetId(string $subnetId): Instance
    {
        $this->subnetId = $subnetId;
        return $this;
    }

    /**
     * @return string
     */
    public function getVpcId(): string
    {
        return $this->vpcId;
    }

    /**
     * @param string $vpcId
     * @return Instance
     */
    public function setVpcId(string $vpcId): Instance
    {
        $this->vpcId = $vpcId;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrivateIpAddress(): string
    {
        return $this->privateIpAddress;
    }

    /**
     * @param string $privateIpAddress
     * @return Instance
     */
    public function setPrivateIpAddress(string $privateIpAddress): Instance
    {
        $this->privateIpAddress = $privateIpAddress;
        return $this;
    }

    /**
     * @return StateReason
     */
    public function getStateReason(): StateReason
    {
        return $this->stateReason;
    }

    /**
     * @param StateReason $stateReason
     * @return Instance
     */
    public function setStateReason(StateReason $stateReason): Instance
    {
        $this->stateReason = $stateReason;
        return $this;
    }

    /**
     * @return string
     */
    public function getRootDeviceName(): string
    {
        return $this->rootDeviceName;
    }

    /**
     * @param string $rootDeviceName
     * @return Instance
     */
    public function setRootDeviceName(string $rootDeviceName): Instance
    {
        $this->rootDeviceName = $rootDeviceName;
        return $this;
    }

    /**
     * @return BlockDeviceMappingCollection
     */
    public function getBlockDeviceMappings(): BlockDeviceMappingCollection
    {
        return $this->blockDeviceMappings;
    }

    /**
     * @param BlockDeviceMappingCollection $blockDeviceMappings
     * @return Instance
     */
    public function setBlockDeviceMappings(BlockDeviceMappingCollection $blockDeviceMappings): Instance
    {
        $this->blockDeviceMappings = $blockDeviceMappings;
        return $this;
    }

    /**
     * @return string
     */
    public function getClientToken(): string
    {
        return $this->clientToken;
    }

    /**
     * @param string $clientToken
     * @return Instance
     */
    public function setClientToken(string $clientToken): Instance
    {
        $this->clientToken = $clientToken;
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
     * @return Instance
     */
    public function setTags(TagCollection $tags): Instance
    {
        $this->tags = $tags;
        return $this;
    }

    /**
     * @return SecurityGroupCollection
     */
    public function getSecurityGroups(): SecurityGroupCollection
    {
        return $this->securityGroups;
    }

    /**
     * @param SecurityGroupCollection $securityGroups
     * @return Instance
     */
    public function setSecurityGroups(SecurityGroupCollection $securityGroups): Instance
    {
        $this->securityGroups = $securityGroups;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSourceDestCheck(): bool
    {
        return $this->sourceDestCheck;
    }

    /**
     * @param bool $sourceDestCheck
     * @return Instance
     */
    public function setSourceDestCheck(bool $sourceDestCheck): Instance
    {
        $this->sourceDestCheck = $sourceDestCheck;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEbsOptimized(): bool
    {
        return $this->ebsOptimized;
    }

    /**
     * @param bool $ebsOptimized
     * @return Instance
     */
    public function setEbsOptimized(bool $ebsOptimized): Instance
    {
        $this->ebsOptimized = $ebsOptimized;
        return $this;
    }

    /**
     * @return NetworkInterfaceCollection
     */
    public function getNetworkInterfaces(): NetworkInterfaceCollection
    {
        return $this->networkInterfaces;
    }

    /**
     * @param NetworkInterfaceCollection $networkInterfaces
     * @return Instance
     */
    public function setNetworkInterfaces(NetworkInterfaceCollection $networkInterfaces): Instance
    {
        $this->networkInterfaces = $networkInterfaces;
        return $this;
    }

    /**
     * @return string
     */
    public function getVirtualizationType(): string
    {
        return $this->virtualizationType;
    }

    /**
     * @param string $virtualizationType
     * @return $this
     */
    public function setVirtualizationType(string $virtualizationType)
    {
        if (! in_array($virtualizationType, self::VIRTUALIZATION_TYPES)) {
            throw new \InvalidArgumentException(
                'An invalid virtualization type was provided:'
                . ' "' . $virtualizationType . '""'
            );
        }

        $this->virtualizationType = $virtualizationType;
        return $this;
    }

    /**
     * @return string
     */
    public function getRootDeviceType(): string
    {
        return $this->rootDeviceType;
    }

    /**
     * @param string $rootDeviceType
     * @return $this
     */
    public function setRootDeviceType(string $rootDeviceType)
    {
        if (! in_array($rootDeviceType, self::ROOT_DEVICE_TYPES)) {
            throw new \InvalidArgumentException(
                'An invalid root device type was provided:'
                . ' "' . $rootDeviceType . '""'
            );
        }

        $this->rootDeviceType = $rootDeviceType;
        return $this;
    }

    /**
     * @return string
     */
    public function getHypervisor(): string
    {
        return $this->hypervisor;
    }

    /**
     * @param string $hypervisor
     * @return $this
     */
    public function setHypervisor(string $hypervisor)
    {
        if (! in_array($hypervisor, self::HYPERVISOR_TYPES)) {
            throw new \InvalidArgumentException(
                'An invalid hypervisor type was provided:'
                . ' "' . $hypervisor . '""'
            );
        }

        $this->hypervisor = $hypervisor;
        return $this;
    }

    /**
     * @return string
     */
    public function getArchitecture(): string
    {
        return $this->architecture;
    }

    /**
     * @param string $architecture
     * @return Instance
     */
    public function setArchitecture(string $architecture): Instance
    {
        if (! in_array($architecture, self::ARCHITECTURE_TYPES)) {
            throw new \InvalidArgumentException(
                'An invalid architecture was provided:'
                . ' "' . $architecture . '"'
            );
        }

        $this->architecture = $architecture;
        return $this;
    }
}
