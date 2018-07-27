<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 04/02/17
 * Time: 15:26
 */

namespace DataSourceBundle\Entity\Aws\ElasticLoadBalancer;

use DataSourceBundle\Entity\Aws\EntityAbstract;

/**
 * Class SourceSecurityGroup
 * @package DataSourceBundle\Entity\Aws\ElasticLoadBalancer
 */
class SourceSecurityGroup extends EntityAbstract
{
    /**
     * @var string
     */
    protected $ownerAlias;

    /**
     * @var string
     */
    protected $groupName;

    /**
     * SourceSecurityGroup constructor.
     * @param string $ownerAlias
     * @param string $groupName
     */
    public function __construct(
        string $ownerAlias,
        string $groupName
    ) {
        $this->setGroupName($groupName)
            ->setOwnerAlias($ownerAlias);
    }

    /**
     * @return string
     */
    public function getOwnerAlias(): string
    {
        return $this->ownerAlias;
    }

    /**
     * @param string $ownerAlias
     * @return SourceSecurityGroup
     */
    public function setOwnerAlias(string $ownerAlias): SourceSecurityGroup
    {
        $this->ownerAlias = $ownerAlias;
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
     * @return SourceSecurityGroup
     */
    public function setGroupName(string $groupName): SourceSecurityGroup
    {
        $this->groupName = $groupName;
        return $this;
    }
}
