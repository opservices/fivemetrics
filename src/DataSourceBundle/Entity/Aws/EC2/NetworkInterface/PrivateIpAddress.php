<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 23/01/17
 * Time: 17:52
 */

namespace DataSourceBundle\Entity\Aws\EC2\NetworkInterface;

use DataSourceBundle\Entity\Aws\EntityAbstract;

/**
 * Class PrivateIpAddress
 * @package DataSourceBundle\Entity\Aws\EC2\NetworkInterface
 */
class PrivateIpAddress extends EntityAbstract
{
    /**
     * @var string
     */
    protected $privateIpAddress;

    /**
     * @var bool
     */
    protected $primary;

    /**
     * @var string
     */
    protected $privateDnsName;

    /**
     * @var Association
     */
    protected $association;

    /**
     * PrivateIpAddress constructor.
     * @param string $privateIpAddress
     * @param bool $primary
     * @param null $privateDnsName
     * @param Association|null $association
     */
    public function __construct(
        string $privateIpAddress,
        bool $primary,
        $privateDnsName = null,
        Association $association = null
    ) {
        $this->setPrivateIpAddress($privateIpAddress)
            ->setPrimary($primary);

        (is_null($privateDnsName)) ?: $this->setPrivateDnsName($privateDnsName);
        (is_null($association)) ?: $this->setAssociation($association);
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
     * @return PrivateIpAddress
     */
    public function setPrivateDnsName(string $privateDnsName): PrivateIpAddress
    {
        $this->privateDnsName = $privateDnsName;
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
     * @return PrivateIpAddress
     */
    public function setPrivateIpAddress(string $privateIpAddress): PrivateIpAddress
    {
        $this->privateIpAddress = $privateIpAddress;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPrimary(): bool
    {
        return $this->primary;
    }

    /**
     * @param bool $primary
     * @return PrivateIpAddress
     */
    public function setPrimary(bool $primary): PrivateIpAddress
    {
        $this->primary = $primary;
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
     * @return PrivateIpAddress
     */
    public function setAssociation(Association $association): PrivateIpAddress
    {
        $this->association = $association;
        return $this;
    }

}
