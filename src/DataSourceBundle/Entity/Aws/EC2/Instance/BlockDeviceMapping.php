<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 20/01/17
 * Time: 15:34
 */

namespace DataSourceBundle\Entity\Aws\EC2\Instance;

use DataSourceBundle\Entity\Aws\EntityAbstract;

/**
 * Class BlockDeviceMapping
 * @package DataSourceBundle\Entity\Aws\EC2\Instance
 */
class BlockDeviceMapping extends EntityAbstract
{
    /**
     * @var string
     */
    protected $deviceName;

    /**
     * @var Ebs
     */
    protected $ebs;

    /**
     * BlockDeviceMapping constructor.
     * @param string $deviceName
     * @param Ebs $ebs
     */
    public function __construct(string $deviceName, Ebs $ebs)
    {
        $this->setDeviceName($deviceName)
            ->setEbs($ebs);
    }

    /**
     * @return string
     */
    public function getDeviceName(): string
    {
        return $this->deviceName;
    }

    /**
     * @param string $deviceName
     * @return BlockDeviceMapping
     */
    public function setDeviceName(string $deviceName): BlockDeviceMapping
    {
        $this->deviceName = $deviceName;
        return $this;
    }

    /**
     * @return Ebs
     */
    public function getEbs(): Ebs
    {
        return $this->ebs;
    }

    /**
     * @param Ebs $ebs
     * @return BlockDeviceMapping
     */
    public function setEbs(Ebs $ebs): BlockDeviceMapping
    {
        $this->ebs = $ebs;
        return $this;
    }
}
