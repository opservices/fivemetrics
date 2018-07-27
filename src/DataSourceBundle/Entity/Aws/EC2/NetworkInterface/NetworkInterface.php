<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 21/01/17
 * Time: 09:35
 */

namespace DataSourceBundle\Entity\Aws\EC2\NetworkInterface;

use DataSourceBundle\Collection\Aws\EC2\PrivateIpAddressCollection;
use DataSourceBundle\Collection\Aws\EC2\SecurityGroupCollection;
use DataSourceBundle\Entity\Aws\EntityAbstract;

/**
 * Class NetworkInterface
 * @package DataSourceBundle\Entity\Aws\EC2\NetworkInterface
 */
class NetworkInterface extends EntityAbstract
{
    const STATUS_TYPES = [
        'available',
        'attaching',
        'in-use',
        'detaching'
    ];

    /**
     * @var string
     */
    protected $networkInterfaceId;

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
    protected $description;

    /**
     * @var string
     */
    protected $ownerId;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var string
     */
    protected $macAddress;

    /**
     * @var string
     */
    protected $privateIpAddress;

    /**
     * @var bool
     */
    protected $sourceDestCheck;

    /**
     * @var SecurityGroupCollection
     */
    protected $groups;

    /**
     * @var Attachment
     */
    protected $attachment;

    /**
     * @var PrivateIpAddressCollection
     */
    protected $privateIpAddresses;

    /**
     * @var Association
     */
    protected $association;

    /**
     * @var string
     */
    protected $privateDnsName;

    /**
     * NetworkInterface constructor.
     * @param string $networkInterfaceId
     * @param string $subnetId
     * @param string $vpcId
     * @param string $description
     * @param string $ownerId
     * @param string $status
     * @param string $macAddress
     * @param string $privateIpAddress
     * @param bool $sourceDestCheck
     * @param SecurityGroupCollection $groups
     * @param Attachment $attachment
     * @param PrivateIpAddressCollection $privateIpAddresses
     * @param Association|null $association
     * @param null $privateDnsName
     */
    public function __construct(
        string $networkInterfaceId,
        string $subnetId,
        string $vpcId,
        string $description,
        string $ownerId,
        string $status,
        string $macAddress,
        string $privateIpAddress,
        bool $sourceDestCheck,
        SecurityGroupCollection $groups,
        Attachment $attachment,
        PrivateIpAddressCollection $privateIpAddresses,
        Association $association = null,
        $privateDnsName = null
    ) {
        $this->setNetworkInterfaceId($networkInterfaceId)
            ->setSubnetId($subnetId)
            ->setVpcId($vpcId)
            ->setDescription($description)
            ->setOwnerId($ownerId)
            ->setStatus($status)
            ->setMacAddress($macAddress)
            ->setPrivateIpAddress($privateIpAddress)
            ->setSourceDestCheck($sourceDestCheck)
            ->setSecurityGroups($groups)
            ->setAttachment($attachment)
            ->setPrivateIpAddresses($privateIpAddresses);

        (is_null($association)) ?: $this->setAssociation($association);
        (is_null($privateDnsName)) ?: $this->setPrivateDnsName($privateDnsName);
    }

    /**
     * @return string|null
     */
    public function getPrivateDnsName()
    {
        return $this->privateDnsName;
    }

    /**
     * @param string $privateDnsName
     * @return NetworkInterface
     */
    public function setPrivateDnsName(string $privateDnsName): NetworkInterface
    {
        $this->privateDnsName = $privateDnsName;
        return $this;
    }

    /**
     * @return Association|null
     */
    public function getAssociation()
    {
        return $this->association;
    }

    /**
     * @param Association $association
     * @return NetworkInterface
     */
    public function setAssociation(Association $association): NetworkInterface
    {
        $this->association = $association;
        return $this;
    }

    /**
     * @return string
     */
    public function getNetworkInterfaceId(): string
    {
        return $this->networkInterfaceId;
    }

    /**
     * @param string $networkInterfaceId
     * @return NetworkInterface
     */
    public function setNetworkInterfaceId(string $networkInterfaceId): NetworkInterface
    {
        $this->networkInterfaceId = $networkInterfaceId;
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
     * @return NetworkInterface
     */
    public function setSubnetId(string $subnetId): NetworkInterface
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
     * @return NetworkInterface
     */
    public function setVpcId(string $vpcId): NetworkInterface
    {
        $this->vpcId = $vpcId;
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
     * @return NetworkInterface
     */
    public function setDescription(string $description): NetworkInterface
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getOwnerId(): string
    {
        return $this->ownerId;
    }

    /**
     * @param string $ownerId
     * @return NetworkInterface
     */
    public function setOwnerId(string $ownerId): NetworkInterface
    {
        $this->ownerId = $ownerId;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return NetworkInterface
     */
    public function setStatus(string $status): NetworkInterface
    {
        if (! in_array($status, self::STATUS_TYPES)) {
            throw new \InvalidArgumentException(
                'An invalid status type was provided:'
                . ' "' . $status . '"'
            );
        }

        $this->status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getMacAddress(): string
    {
        return $this->macAddress;
    }

    /**
     * @param string $macAddress
     * @return NetworkInterface
     */
    public function setMacAddress(string $macAddress): NetworkInterface
    {
        $this->macAddress = $macAddress;
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
     * @return NetworkInterface
     */
    public function setPrivateIpAddress(string $privateIpAddress): NetworkInterface
    {
        $this->privateIpAddress = $privateIpAddress;
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
     * @return NetworkInterface
     */
    public function setSourceDestCheck(bool $sourceDestCheck): NetworkInterface
    {
        $this->sourceDestCheck = $sourceDestCheck;
        return $this;
    }

    /**
     * @return SecurityGroupCollection
     */
    public function getGroups(): SecurityGroupCollection
    {
        return $this->groups;
    }

    /**
     * @param SecurityGroupCollection $groups
     * @return NetworkInterface
     */
    public function setSecurityGroups(SecurityGroupCollection $groups): NetworkInterface
    {
        $this->groups = $groups;
        return $this;
    }

    /**
     * @return Attachment
     */
    public function getAttachment(): Attachment
    {
        return $this->attachment;
    }

    /**
     * @param Attachment $attachment
     * @return NetworkInterface
     */
    public function setAttachment(Attachment $attachment): NetworkInterface
    {
        $this->attachment = $attachment;
        return $this;
    }

    /**
     * @return PrivateIpAddressCollection
     */
    public function getPrivateIpAddresses(): PrivateIpAddressCollection
    {
        return $this->privateIpAddresses;
    }

    /**
     * @param PrivateIpAddressCollection $privateIpAddresses
     * @return NetworkInterface
     */
    public function setPrivateIpAddresses(PrivateIpAddressCollection $privateIpAddresses): NetworkInterface
    {
        $this->privateIpAddresses = $privateIpAddresses;
        return $this;
    }
}
