<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 03/02/17
 * Time: 10:50
 */

namespace DataSourceBundle\Entity\Aws\AutoScaling;

use DataSourceBundle\Entity\Aws\Tag\Tag as GenericTag;

/**
 * Class Tag
 * @package DataSourceBundle\Entity\Aws\AutoScaling
 */
class Tag extends GenericTag
{
    /**
     * @var string
     */
    protected $resourceId;

    /**
     * @var string
     */
    protected $resourceType;

    /**
     * @var bool
     */
    protected $propagateAtLaunch;

    /**
     * Tag constructor.
     * @param string $resourceId
     * @param string $resourceType
     * @param bool $propagateAtLaunch
     * @param string $key
     * @param string $value
     */
    public function __construct(
        string $resourceId,
        string $resourceType,
        bool $propagateAtLaunch,
        string $key,
        string $value = ''
    ) {
        parent::__construct($key, $value);

        $this->setResourceId($resourceId)
            ->setResourceType($resourceType)
            ->setPropagateAtLaunch($propagateAtLaunch);
    }

    /**
     * @return string
     */
    public function getResourceId(): string
    {
        return $this->resourceId;
    }

    /**
     * @param string $resourceId
     * @return Tag
     */
    public function setResourceId(string $resourceId): Tag
    {
        $this->resourceId = $resourceId;
        return $this;
    }

    /**
     * @return string
     */
    public function getResourceType(): string
    {
        return $this->resourceType;
    }

    /**
     * @param string $resourceType
     * @return Tag
     */
    public function setResourceType(string $resourceType): Tag
    {
        $this->resourceType = $resourceType;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPropagateAtLaunch(): bool
    {
        return $this->propagateAtLaunch;
    }

    /**
     * @param bool $propagateAtLaunch
     * @return Tag
     */
    public function setPropagateAtLaunch(bool $propagateAtLaunch): Tag
    {
        $this->propagateAtLaunch = $propagateAtLaunch;
        return $this;
    }

}
