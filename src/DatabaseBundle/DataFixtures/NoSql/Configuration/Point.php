<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 10/08/17
 * Time: 15:07
 */

namespace DatabaseBundle\DataFixtures\NoSql\Configuration;

use EssentialsBundle\Entity\EntityAbstract;

/**
 * Class Point
 * @package DatabaseBundle\DataFixtures\NoSql\Configuration
 */
class Point extends EntityAbstract
{
    /**
     * @var Value
     */
    protected $value;

    /**
     * @var Value
     */
    protected $minimum;

    /**
     * @var Value
     */
    protected $maximum;

    /**
     * @var Value
     */
    protected $sampleCount;

    /**
     * @var Value
     */
    protected $sum;

    /**
     * Point constructor.
     * @param Value $value
     * @param Value $minimum
     * @param Value $maximum
     * @param Value $sampleCount
     * @param Value $sum
     */
    public function __construct(
        Value $value,
        Value $minimum = null,
        Value $maximum = null,
        Value $sampleCount = null,
        Value $sum = null
    ) {
        $this->value = $value;
        $this->minimum = $minimum;
        $this->maximum = $maximum;
        $this->sampleCount = $sampleCount;
        $this->sum = $sum;
    }

    /**
     * @return Value
     */
    public function getValue(): Value
    {
        return $this->value;
    }

    /**
     * @return Value|null
     */
    public function getMinimum()
    {
        return $this->minimum;
    }

    /**
     * @return Value|null
     */
    public function getMaximum()
    {
        return $this->maximum;
    }

    /**
     * @return Value|null
     */
    public function getSampleCount()
    {
        return $this->sampleCount;
    }

    /**
     * @return Value|null
     */
    public function getSum()
    {
        return $this->sum;
    }
}
