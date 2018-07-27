<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 10/08/17
 * Time: 15:58
 */

namespace DatabaseBundle\DataFixtures\NoSql\Configuration;

use EssentialsBundle\Entity\EntityAbstract;

/**
 * Class Range
 * @package DatabaseBundle\DataFixtures\NoSql\Configuration
 */
class Range extends EntityAbstract
{
    /**
     * @var int
     */
    protected $minimum;

    /**
     * @var int
     */
    protected $maximum;

    /**
     * Range constructor.
     * @param int $minimum
     * @param int $maximum
     */
    public function __construct(int $minimum, int $maximum)
    {
        $this->minimum = $minimum;
        $this->maximum = $maximum;
    }

    /**
     * @return int
     */
    public function getMinimum(): int
    {
        return $this->minimum;
    }

    /**
     * @return int
     */
    public function getMaximum(): int
    {
        return $this->maximum;
    }
}
