<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 04/02/17
 * Time: 15:53
 */

namespace DataSourceBundle\Entity\Aws\ElasticLoadBalancer\ListenerDescription;

use DataSourceBundle\Entity\Aws\EntityAbstract;

/**
 * Class Listener
 * @package DataSourceBundle\Entity\Aws\ElasticLoadBalancer\ListenerDescription
 */
class Listener extends EntityAbstract
{
    /**
     * @var int
     */
    protected $instancePort;

    /**
     * @var string
     */
    protected $instanceProtocol;

    /**
     * @var int
     */
    protected $loadBalancerPort;

    /**
     * @var string
     */
    protected $protocol;

    /**
     * @var string
     */
    protected $SSLCertificateId;

    /**
     * Listener constructor.
     * @param int $instancePort
     * @param string $instanceProtocol
     * @param int $loadBalancerPort
     * @param string $protocol
     * @param string $SSLCertificateId
     */
    public function __construct(
        int $instancePort,
        string $instanceProtocol,
        int $loadBalancerPort,
        string $protocol,
        string $SSLCertificateId = null
    ) {

        $this->setInstancePort($instancePort)
            ->setInstanceProtocol($instanceProtocol)
            ->setLoadBalancerPort($loadBalancerPort)
            ->setProtocol($protocol)
            ->setSSLCertificateId(
                (empty($SSLCertificateId)) ? '' : $SSLCertificateId
            );
    }

    /**
     * @return int
     */
    public function getInstancePort(): int
    {
        return $this->instancePort;
    }

    /**
     * @param int $instancePort
     * @return Listener
     */
    public function setInstancePort(int $instancePort): Listener
    {
        $this->instancePort = $instancePort;
        return $this;
    }

    /**
     * @return string
     */
    public function getInstanceProtocol(): string
    {
        return $this->instanceProtocol;
    }

    /**
     * @param string $instanceProtocol
     * @return Listener
     */
    public function setInstanceProtocol(string $instanceProtocol): Listener
    {
        $this->instanceProtocol = $instanceProtocol;
        return $this;
    }

    /**
     * @return int
     */
    public function getLoadBalancerPort(): int
    {
        return $this->loadBalancerPort;
    }

    /**
     * @param int $loadBalancerPort
     * @return Listener
     */
    public function setLoadBalancerPort(int $loadBalancerPort): Listener
    {
        $this->loadBalancerPort = $loadBalancerPort;
        return $this;
    }

    /**
     * @return string
     */
    public function getProtocol(): string
    {
        return $this->protocol;
    }

    /**
     * @param string $protocol
     * @return Listener
     */
    public function setProtocol(string $protocol): Listener
    {
        $this->protocol = $protocol;
        return $this;
    }

    /**
     * @return string
     */
    public function getSSLCertificateId(): string
    {
        return $this->SSLCertificateId;
    }

    /**
     * @param string $SSLCertificateId
     * @return Listener
     */
    public function setSSLCertificateId(string $SSLCertificateId): Listener
    {
        $this->SSLCertificateId = $SSLCertificateId;
        return $this;
    }
}
