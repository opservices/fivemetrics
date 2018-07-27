<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 13/02/17
 * Time: 14:40
 */

namespace DataSourceBundle\Entity\Aws\EC2\Subnet;

use DataSourceBundle\Collection\Aws\Tag\TagCollection;
use DataSourceBundle\Collection\Aws\EC2\Subnet\Ipv6\Ipv6CidrBlockAssociationCollection;
use DataSourceBundle\Entity\Aws\EntityAbstract;

/**
 * Class Subnet
 * @package DataSourceBundle\Entity\Aws\EC2\Subnet
 */
class Subnet extends EntityAbstract
{
    const STATE_TYPES = [
        'pending',
        'available'
    ];

    /**
     * @var bool
     */
    protected $assignIpv6AddressOnCreation;

    /**
     * @var string
     */
    protected $availabilityZone;

    /**
     * @var int
     */
    protected $availableIpAddressCount;

    /**
     * @var string
     */
    protected $cidrBlock;

    /**
     * @var bool
     */
    protected $defaultForAz;

    /**
     * @var Ipv6CidrBlockAssociationCollection
     */
    protected $ipv6CidrBlockAssociationSet;

    /**
     * @var bool
     */
    protected $mapPublicIpOnLaunch;

    /**
     * @var string
     */
    protected $state;

    /**
     * @var string
     */
    protected $subnetId;

    /**
     * @var TagCollection
     */
    protected $tags;

    /**
     * @var string
     */
    protected $vpcId;

    /**
     * Subnet constructor.
     * @param string $vpcId
     * @param string $subnetId
     * @param string $state
     * @param string $availabilityZone
     * @param int $availableIpAddressCount
     * @param string $cidrBlock
     * @param bool $defaultForAz
     * @param bool $mapPublicIpOnLaunch
     * @param bool|null $assignIpv6AddressOnCreation
     * @param TagCollection|null $tags
     * @param Ipv6CidrBlockAssociationCollection|null $ipv6CidrBlockAssociationSet
     */
    public function __construct(
        string $vpcId,
        string $subnetId,
        string $state,
        string $availabilityZone,
        int $availableIpAddressCount,
        string $cidrBlock,
        bool $defaultForAz,
        bool $mapPublicIpOnLaunch,
        bool $assignIpv6AddressOnCreation = null,
        TagCollection $tags = null,
        Ipv6CidrBlockAssociationCollection $ipv6CidrBlockAssociationSet = null
    ) {
        $this->setAvailabilityZone($availabilityZone)
            ->setAvailableIpAddressCount($availableIpAddressCount)
            ->setCidrBlock($cidrBlock)
            ->setDefaultForAz($defaultForAz)
            ->setMapPublicIpOnLaunch($mapPublicIpOnLaunch)
            ->setState($state)
            ->setSubnetId($subnetId)
            ->setVpcId($vpcId);

        $this->setTags((is_null($tags)) ? new TagCollection() : $tags)
            ->setIpv6CidrBlockAssociationSet(
                (is_null($ipv6CidrBlockAssociationSet))
                ? new Ipv6CidrBlockAssociationCollection()
                : $ipv6CidrBlockAssociationSet
            );

        $this->setAssignIpv6AddressOnCreation(!!$assignIpv6AddressOnCreation);
    }

    /**
     * @return bool
     */
    public function isAssignIpv6AddressOnCreation(): bool
    {
        return $this->assignIpv6AddressOnCreation;
    }

    /**
     * @param bool $assignIpv6AddressOnCreation
     * @return Subnet
     */
    public function setAssignIpv6AddressOnCreation(
        bool $assignIpv6AddressOnCreation
    ): Subnet {
        $this->assignIpv6AddressOnCreation = $assignIpv6AddressOnCreation;
        return $this;
    }

    /**
     * @return string
     */
    public function getAvailabilityZone(): string
    {
        return $this->availabilityZone;
    }

    /**
     * @param string $availabilityZone
     * @return Subnet
     */
    public function setAvailabilityZone(string $availabilityZone): Subnet
    {
        $this->availabilityZone = $availabilityZone;
        return $this;
    }

    /**
     * @return int
     */
    public function getAvailableIpAddressCount(): int
    {
        return $this->availableIpAddressCount;
    }

    /**
     * @param int $availableIpAddressCount
     * @return Subnet
     */
    public function setAvailableIpAddressCount(
        int $availableIpAddressCount
    ): Subnet {
        $this->availableIpAddressCount = $availableIpAddressCount;
        return $this;
    }

    /**
     * @return string
     */
    public function getCidrBlock(): string
    {
        return $this->cidrBlock;
    }

    /**
     * @param string $cidrBlock
     * @return Subnet
     */
    public function setCidrBlock(string $cidrBlock): Subnet
    {
        $this->cidrBlock = $cidrBlock;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDefaultForAz(): bool
    {
        return $this->defaultForAz;
    }

    /**
     * @param bool $defaultForAz
     * @return Subnet
     */
    public function setDefaultForAz(bool $defaultForAz): Subnet
    {
        $this->defaultForAz = $defaultForAz;
        return $this;
    }

    /**
     * @return Ipv6CidrBlockAssociationCollection
     */
    public function getIpv6CidrBlockAssociationSet(): Ipv6CidrBlockAssociationCollection
    {
        return $this->ipv6CidrBlockAssociationSet;
    }

    /**
     * @param Ipv6CidrBlockAssociationCollection $ipv6CidrBlockAssociationSet
     * @return Subnet
     */
    public function setIpv6CidrBlockAssociationSet(
        Ipv6CidrBlockAssociationCollection $ipv6CidrBlockAssociationSet
    ): Subnet {
        $this->ipv6CidrBlockAssociationSet = $ipv6CidrBlockAssociationSet;
        return $this;
    }

    /**
     * @return bool
     */
    public function isMapPublicIpOnLaunch(): bool
    {
        return $this->mapPublicIpOnLaunch;
    }

    /**
     * @param bool $mapPublicIpOnLaunch
     * @return Subnet
     */
    public function setMapPublicIpOnLaunch(bool $mapPublicIpOnLaunch): Subnet
    {
        $this->mapPublicIpOnLaunch = $mapPublicIpOnLaunch;
        return $this;
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
     * @return Subnet
     */
    public function setState(string $state): Subnet
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubnetId(): string
    {
        return $this->subnetId;
    }

    /**
     * @param string $subnetId
     * @return Subnet
     */
    public function setSubnetId(string $subnetId): Subnet
    {
        $this->subnetId = $subnetId;
        return $this;
    }

    /**
     * @return TagCollection
     */
    public function getTags(): TagCollection
    {
        return is_null($this->tags) ? new TagCollection() : $this->tags;
    }

    /**
     * @param TagCollection $tags
     * @return Subnet
     */
    public function setTags(TagCollection $tags): Subnet
    {
        $this->tags = $tags;
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
     * @return Subnet
     */
    public function setVpcId(string $vpcId): Subnet
    {
        $this->vpcId = $vpcId;
        return $this;
    }
}
