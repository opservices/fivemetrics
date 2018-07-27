<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 6/6/17
 * Time: 9:34 AM
 */

namespace DataSourceBundle\Entity\Aws\S3\Bucket;

use DataSourceBundle\Collection\Aws\Tag\TagCollection;
use DataSourceBundle\Entity\Aws\EntityAbstract;

/**
 * Class Bucket
 * @package DataSourceBundle\Entity\Aws\S3\Bucket
 */
class Bucket extends EntityAbstract
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var Versioning
     */
    protected $versioning;

    /**
     * @var Location
     */
    protected $location;

    /**
     * @var TagCollection
     */
    protected $tags;

    /**
     * Bucket constructor.
     * @param string $name
     * @param Versioning|null $versioning
     * @param Location|null $location
     * @param TagCollection|null $tags
     */
    public function __construct(
        string $name,
        Versioning $versioning = null,
        Location $location = null,
        TagCollection $tags = null
    ) {
        $this->setName($name);
        (empty($versioning)) ?: $this->setVersioning($versioning);
        (empty($location)) ?: $this->setLocation($location);
        (empty($tags)) ?: $this->setTags($tags);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Bucket
     */
    public function setName(string $name): Bucket
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Versioning
     */
    public function getVersioning(): Versioning
    {
        if (is_null($this->versioning)) {
            throw new \RuntimeException("Versioning is null");
        }
        return $this->versioning;
    }

    /**
     * @param Versioning $versioning
     * @return Bucket
     */
    public function setVersioning(Versioning $versioning): Bucket
    {
        $this->versioning = $versioning;
        return $this;
    }

    /**
     * @return Location
     * @throws \Exception
     */
    public function getLocation() : Location
    {
        if (is_null($this->location)) {
            throw new \RuntimeException("Location is null");
        }
        return $this->location;
    }

    /**
     * @param Location $location
     * @return Bucket
     */
    public function setLocation(Location $location): Bucket
    {
        $this->location = $location;
        return $this;
    }

    /**
     * @return TagCollection
     */
    public function getTags(): TagCollection
    {
        return $this->tags;
    }

    /**
     * @param TagCollection $tags
     * @return Bucket
     */
    public function setTags(TagCollection $tags): Bucket
    {
        $this->tags = $tags;
        return $this;
    }
}
