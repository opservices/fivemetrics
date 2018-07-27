<?php

namespace DataSourceBundle\Entity\Aws\EC2\SecurityGroup;

use DataSourceBundle\Entity\Aws\EntityAbstract;

/**
 * Class SecurityGroup
 * @package DataSourceBundle\Entity\Aws\EC2\SecurityGroup
 */
class SecurityGroup extends EntityAbstract
{
    /**
     * @var string
     */
    protected $groupName;

    /**
     * @var string
     */
    protected $groupId;

    /**
     * SecurityGroup constructor.
     * @param string $groupName
     * @param string $groupId
     */
    public function __construct(string $groupName, string $groupId)
    {
        $this->setGroupName($groupName)
            ->setGroupId($groupId);
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
     * @return SecurityGroup
     */
    public function setGroupName(string $groupName): SecurityGroup
    {
        $this->groupName = $groupName;
        return $this;
    }

    /**
     * @return string
     */
    public function getGroupId(): string
    {
        return $this->groupId;
    }

    /**
     * @param string $groupId
     * @return SecurityGroup
     */
    public function setGroupId(string $groupId): SecurityGroup
    {
        $this->groupId = $groupId;
        return $this;
    }
}