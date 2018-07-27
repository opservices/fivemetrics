<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 23/01/17
 * Time: 17:31
 */

namespace DataSourceBundle\Entity\Aws\EC2\NetworkInterface;

use DataSourceBundle\Entity\Aws\EntityAbstract;

/**
 * Class Association
 * @package DataSourceBundle\Entity\Aws\EC2\NetworkInterface
 */
class Association extends EntityAbstract
{
    /**
     * @var string
     */
    protected $publicIp;

    /**
     * @var string
     */
    protected $publicDnsName;

    /**
     * @var string
     */
    protected $ipOwnerId;

    /**
     * Association constructor.
     * @param string $publicIp
     * @param string $publicDnsName
     * @param string $ipOwnerId
     */
    public function __construct(
        string $publicIp,
        string $publicDnsName,
        string $ipOwnerId
    ) {
        $this->setIpOwnerId($ipOwnerId)
            ->setPublicDnsName($publicDnsName)
            ->setPublicIp($publicIp);
    }

    /**
     * @return string
     */
    public function getPublicIp(): string
    {
        return $this->publicIp;
    }

    /**
     * @param string $publicIp
     * @return Association
     */
    public function setPublicIp(string $publicIp): Association
    {
        $this->publicIp = $publicIp;
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
     * @return Association
     */
    public function setPublicDnsName(string $publicDnsName): Association
    {
        $this->publicDnsName = $publicDnsName;
        return $this;
    }

    /**
     * @return string
     */
    public function getIpOwnerId(): string
    {
        return $this->ipOwnerId;
    }

    /**
     * @param string $ipOwnerId
     * @return Association
     */
    public function setIpOwnerId(string $ipOwnerId): Association
    {
        $this->ipOwnerId = $ipOwnerId;
        return $this;
    }
}
