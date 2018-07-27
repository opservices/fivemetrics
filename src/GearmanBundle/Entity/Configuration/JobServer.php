<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 23/02/17
 * Time: 16:20
 */

namespace GearmanBundle\Entity\Configuration;

use EssentialsBundle\Entity\EntityAbstract;

/**
 * Class JobServer
 * @package GearmanBundle\Entity\Configuration
 */
class JobServer extends EntityAbstract
{
    /**
     * @var string
     */
    protected $address;

    /**
     * @var int
     */
    protected $port;

    /**
     * JobServer constructor.
     * @param string $address
     * @param int $port
     */
    public function __construct(string $address, int $port = 4730)
    {
        $this->setAddress($address)
            ->setPort($port);
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @param int $port
     * @return JobServer
     */
    public function setPort(int $port): JobServer
    {
        $this->port = $port;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return JobServer
     */
    public function setAddress(string $address): JobServer
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getAddress() . ':' . $this->getPort();
    }
}

