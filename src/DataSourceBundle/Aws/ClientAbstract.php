<?php

/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 17/12/16
 * Time: 19:50
 */

namespace DataSourceBundle\Aws;

use Aws\Credentials\Credentials;
use DataSourceBundle\Entity\Aws\Region\RegionInterface;

/**
 * Class ClientAbstract
 * @package Connector
 */
abstract class ClientAbstract
{
    /**
     * @var Credentials
     */
    protected $credential;

    /**
     * @var RegionInterface
     */
    protected $region;

    /**
     * DataSource constructor.
     *
     * @param string $key
     * @param string $secret
     * @param RegionInterface $region
     */
    public function __construct(string $key, string $secret, RegionInterface $region)
    {
        $this->credential = new Credentials($key, $secret);
        $this->setRegion($region);
    }

    /**
     * @return RegionInterface
     */
    public function getRegion(): RegionInterface
    {
        return $this->region;
    }

    /**
     * @param RegionInterface $region
     * @return ClientAbstract
     */
    public function setRegion(RegionInterface $region): ClientAbstract
    {
        $this->region = $region;
        return $this;
    }

    /**
     * @return Credentials
     */
    public function getCredential(): Credentials
    {
        return $this->credential;
    }

    /**
     * @param Credentials $credential
     * @return $this
     */
    public function setCredential(Credentials $credential)
    {
        $this->credential = $credential;
        return $this;
    }


    /**
     * Verify if the given credential have needed permissions
     *
     * @throw AwsException
     * @return bool
     */
    abstract public function checkCredential(): bool;
}
