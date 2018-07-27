<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 10/02/17
 * Time: 10:18
 */

namespace EssentialsBundle\Entity\Metric;

use EssentialsBundle\Collection\Tag\TagCollection;
use EssentialsBundle\Entity\EntityAbstract;
use EssentialsBundle\Entity\DateTime\DateTime;
use InfluxDB\Point as InfluxPoint;

/**
 * Class Point
 * @package EssentialsBundle\Entity\Metric
 */
class Point extends EntityAbstract
{
    /**
     * @var string
     */
    protected $value;

    /**
     * @var string
     */
    protected $maximum;

    /**
     * @var string
     */
    protected $minimum;

    /**
     * @var string
     */
    protected $sampleCount;

    /**
     * @var string
     */
    protected $sum;

    /**
     * @var DateTime
     */
    protected $time;

    /**
     * @var string
     */
    protected $unit;

    /**
     * Point constructor.
     * @param string $value
     * @param string|null $minimum
     * @param string|null $maximum
     * @param string|null $sampleCount
     * @param string|null $sum
     * @param DateTime|null $time
     * @param string|null $unit
     */
    public function __construct(
        string $value,
        string $minimum = null,
        string $maximum = null,
        string $sampleCount = null,
        string $sum = null,
        DateTime $time = null,
        string $unit = null
    ) {
        $this->setValue($value)
            ->setUnit((is_null($unit)) ? 'Count' : $unit)
            ->setTime((is_null($time)) ? new DateTime() : $time);

        if (! is_null($maximum)) {
            $this->setMaximum($maximum);
        }

        if (! is_null($minimum)) {
            $this->setMinimum($minimum);
        }

        if (! is_null($sampleCount)) {
            $this->setSampleCount($sampleCount);
        }

        if (! is_null($sum)) {
            $this->setSum($sum);
        }
    }

    /**
     * @return string|null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return Point
     */
    public function setValue(string $value): Point
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMaximum()
    {
        return $this->maximum;
    }

    /**
     * @param string $maximum
     * @return Point
     */
    public function setMaximum(string $maximum): Point
    {
        $this->maximum = $maximum;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMinimum()
    {
        return $this->minimum;
    }

    /**
     * @param string $minimum
     * @return Point
     */
    public function setMinimum(string $minimum): Point
    {
        $this->minimum = $minimum;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSampleCount()
    {
        return $this->sampleCount;
    }

    /**
     * @param string $sampleCount
     * @return Point
     */
    public function setSampleCount(string $sampleCount): Point
    {
        $this->sampleCount = $sampleCount;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSum()
    {
        return $this->sum;
    }

    /**
     * @param string $sum
     * @return Point
     */
    public function setSum(string $sum): Point
    {
        $this->sum = $sum;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getTime(): DateTime
    {
        return $this->time;
    }

    /**
     * @param DateTime $time
     * @return Point
     */
    public function setTime(DateTime $time): Point
    {
        $this->time = $time;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * @param string $unit
     * @return Point
     */
    public function setUnit(string $unit): Point
    {
        $this->unit = $unit;
        return $this;
    }

    public function toInfluxPoint(string $measurementName, TagCollection $tags)
    {
        $value = $this->getValue();

        return new InfluxPoint(
            $measurementName,
            (is_numeric($value)) ? (float)$value : $value,
            array_merge($tags->toInfluxTagArray(), [ 'unit' => $this->getUnit() ]),
            [
                'minimum'     => (integer)$this->getMinimum(),
                'maximum'     => (integer)$this->getMaximum(),
                'sampleCount' => (integer)$this->getSampleCount(),
                'sum'         => (integer)$this->getSum()
            ],
            $this->getTime()->format('U')
        );
    }
}
