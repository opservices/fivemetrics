<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 20/01/17
 * Time: 12:21
 */

namespace DataSourceBundle\Entity\Aws\EC2\Instance;

use DataSourceBundle\Entity\Aws\EntityAbstract;

/**
 * Class Placement
 * @package DataSourceBundle\Entity\Aws\EC2\Instance
 */
class Placement extends EntityAbstract
{
    const TENANCY_TYPES = [
        'default',
        'dedicated',
        'host'
    ];

    /**
     * @var string
     */
    protected $availabilityZone;

    /**
     * @var string
     */
    protected $groupName;

    /**
     * @var string
     */
    protected $tenancy;

    /**
     * @var string
     */
    protected $hostId;

    /**
     * @var string
     */
    protected $affinity;

    /**
     * Placement constructor.
     * @param string $availabilityZone
     * @param string $tenancy
     * @param string $groupName
     * @param null $hostId
     * @param null $affinity
     */
    public function __construct(
        string $availabilityZone,
        string $tenancy = 'default',
        string $groupName = '',
        $hostId = null,
        $affinity = null
    ) {
        $this->setAvailabilityZone($availabilityZone)
            ->setTenancy($tenancy)
            ->setGroupName($groupName);

        (is_null($hostId)) ?: $this->setHostId($hostId);
        (is_null($affinity)) ?: $this->setAffinity($affinity);
    }

    /**
     * @return string|null
     */
    public function getHostId()
    {
        return $this->hostId;
    }

    /**
     * @param string $hostId
     * @return Placement
     */
    public function setHostId(string $hostId): Placement
    {
        $this->hostId = $hostId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAffinity()
    {
        return $this->affinity;
    }

    /**
     * @param string $affinity
     * @return Placement
     */
    public function setAffinity(string $affinity): Placement
    {
        $this->affinity = $affinity;
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
     * @return Placement
     */
    public function setAvailabilityZone(string $availabilityZone): Placement
    {
        $this->availabilityZone = $availabilityZone;
        return $this;
    }

    /**
     * @return string
     */
    public function getGroupName(): string
    {
        return $this->groupName;
    }

    /**
     * @param string $groupName
     * @return Placement
     */
    public function setGroupName(string $groupName): Placement
    {
        $this->groupName = $groupName;
        return $this;
    }

    /**
     * @return string
     */
    public function getTenancy(): string
    {
        return $this->tenancy;
    }

    /**
     * @param string $tenancy 'default|dedicated|host'
     * @return Placement
     */
    public function setTenancy(string $tenancy): Placement
    {
        if (! in_array($tenancy, self::TENANCY_TYPES)) {
            throw new \InvalidArgumentException(
                'An invalid tenancy type was provided:'
                . ' "' . $tenancy . '"'
            );
        }

        $this->tenancy = $tenancy;
        return $this;
    }
}
