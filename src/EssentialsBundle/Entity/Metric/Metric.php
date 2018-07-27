<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 18/01/17
 * Time: 13:06
 */

namespace EssentialsBundle\Entity\Metric;

use EssentialsBundle\Collection\Metric\PointCollection;
use EssentialsBundle\Collection\Tag\TagCollection;
use EssentialsBundle\Entity\EntityAbstract;
use EssentialsBundle\Exception\Exceptions;

class Metric extends EntityAbstract
{
    const MAX_ALLOWED_TAGS = 50;

    /**
     * @var TagCollection
     */
    protected $tags;

    /**
     * @var PointCollection
     */
    protected $points;

    /**
     * @var string
     */
    protected $name;

    /**
     * Metric constructor.
     * @param string $name
     * @param TagCollection $tags
     * @param PointCollection $points
     */
    public function __construct(
        string $name,
        TagCollection $tags = null,
        PointCollection $points = null
    ) {
        $this->setName($name)
            ->setTags(($tags) ? $tags : new TagCollection())
            ->setPoints(($points) ? $points : new PointCollection());
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
     * @return Metric
     */
    public function setName(string $name): Metric
    {
        if (! preg_match('/^[0-9a-zA-Z._\-\/]+$/', $name)) {
            throw new \InvalidArgumentException(
                'The metric name must not be empty and must contain letters,'
                . ' numbers, underscore, hyphen, dot or slashes.',
                Exceptions::VALIDATION_ERROR
            );
        }

        $this->name = $name;
        return $this;
    }

    /**
     * @return PointCollection
     */
    public function getPoints(): PointCollection
    {
        return $this->points;
    }

    /**
     * @param PointCollection $points
     * @return Metric
     */
    public function setPoints(PointCollection $points): Metric
    {
        $this->points = $points;
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
     * @return Metric
     */
    public function setTags(TagCollection $tags): Metric
    {
        if (count($tags) >= self::MAX_ALLOWED_TAGS) {
            throw new \InvalidArgumentException(
                'The maximum number of tags allowed by metric is '
                . self::MAX_ALLOWED_TAGS . '.',
                Exceptions::VALIDATION_ERROR
            );
        }

        $this->tags = $tags;
        return $this;
    }

    /**
     * @return array
     */
    public function toInfluxPoints(): array
    {
        return $this->points->toInfluxPoints(
            $this->getName(),
            $this->getTags()
        );
    }
}
