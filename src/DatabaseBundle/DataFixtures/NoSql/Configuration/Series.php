<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 10/08/17
 * Time: 14:49
 */

namespace DatabaseBundle\DataFixtures\NoSql\Configuration;

use EssentialsBundle\Entity\EntityAbstract;

/**
 * Class Series
 * @package DatabaseBundle\DataFixtures\NoSql\Configuration
 */
class Series extends EntityAbstract
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $total;

    /**
     * @var int
     */
    protected $interval;

    /**
     * @var TagCollection
     */
    protected $tags;

    /**
     * @var Point
     */
    protected $point;

    /**
     * Series constructor.
     * @param string $name
     * @param int $total
     * @param int $interval
     */
    public function __construct(
        string $name,
        int $total,
        int $interval,
        TagCollection $tags,
        Point $point
    ) {
        $this->name  = $name;
        $this->total = $total;
        $this->interval = $interval;
        $this->tags  = $tags;
        $this->point = $point;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @return int
     */
    public function getInterval(): int
    {
        return $this->interval;
    }

    /**
     * @return TagCollection
     */
    public function getTags(): TagCollection
    {
        return $this->tags;
    }

    /**
     * @return Point
     */
    public function getPoint(): Point
    {
        return $this->point;
    }
}
