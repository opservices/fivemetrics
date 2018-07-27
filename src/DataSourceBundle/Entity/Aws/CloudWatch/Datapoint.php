<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 10/02/17
 * Time: 10:18
 */

namespace DataSourceBundle\Entity\Aws\CloudWatch;

use DataSourceBundle\Entity\Aws\EntityAbstract;
use EssentialsBundle\Entity\DateTime\DateTime;

/**
 * Class Datapoint
 * @package Entity\Aws\CloudWatch
 */
class Datapoint extends EntityAbstract
{
    const UNIT_TYPES = [
        'Seconds',
        'Microseconds',
        'Milliseconds',
        'Bytes',
        'Kilobytes',
        'Megabytes',
        'Gigabytes',
        'Terabytes',
        'Bits',
        'Kilobits',
        'Megabits',
        'Gigabits',
        'Terabits',
        'Percent',
        'Count',
        'Bytes/Second',
        'Kilobytes/Second',
        'Megabytes/Second',
        'Gigabytes/Second',
        'Terabytes/Second',
        'Bits/Second',
        'Kilobits/Second',
        'Megabits/Second',
        'Gigabits/Second',
        'Terabits/Second',
        'Count/Second',
        'None'
    ];

    /**
     * @var string
     */
    protected $average;

    /**
     * @var array
     */
    protected $extendedStatistics;

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
    protected $timestamp;

    /**
     * @var string
     */
    protected $unit;

    /**
     * @var string
     */
    protected $label;

    /**
     * Datapoint constructor.
     * @param string $average
     * @param string $maximum
     * @param string $minimum
     * @param string $sampleCount
     * @param string $sum
     * @param DateTime $timestamp
     * @param string $unit
     * @param string|null $label
     * @param array|null $extendedStatistics
     */
    public function __construct(
        string $average,
        string $maximum,
        string $minimum,
        string $sampleCount,
        string $sum,
        DateTime $timestamp,
        string $unit,
        string $label = null,
        array $extendedStatistics = null
    ) {
        $this->setAverage($average)
            ->setMaximum($maximum)
            ->setMinimum($minimum)
            ->setSampleCount($sampleCount)
            ->setSum($sum)
            ->setTimestamp($timestamp)
            ->setUnit($unit);

        (is_null($label)) ?: $this->setLabel($label);
        (is_null($extendedStatistics)) ?: $this->setExtendedStatistics($extendedStatistics);
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return Datapoint
     */
    public function setLabel(string $label): Datapoint
    {
        $this->label = $label;
        return $this;
    }

    /**
     * @return string
     */
    public function getAverage(): string
    {
        return $this->average;
    }

    /**
     * @param string $average
     * @return Datapoint
     */
    public function setAverage(string $average): Datapoint
    {
        $this->average = $average;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getExtendedStatistics()
    {
        return $this->extendedStatistics;
    }

    /**
     * @param array $extendedStatistics
     * @return Datapoint
     */
    public function setExtendedStatistics(array $extendedStatistics): Datapoint
    {
        $this->extendedStatistics = $extendedStatistics;
        return $this;
    }

    /**
     * @return string
     */
    public function getMaximum(): string
    {
        return $this->maximum;
    }

    /**
     * @param string $maximum
     * @return Datapoint
     */
    public function setMaximum(string $maximum): Datapoint
    {
        $this->maximum = $maximum;
        return $this;
    }

    /**
     * @return string
     */
    public function getMinimum(): string
    {
        return $this->minimum;
    }

    /**
     * @param string $minimum
     * @return Datapoint
     */
    public function setMinimum(string $minimum): Datapoint
    {
        $this->minimum = $minimum;
        return $this;
    }

    /**
     * @return string
     */
    public function getSampleCount(): string
    {
        return $this->sampleCount;
    }

    /**
     * @param string $sampleCount
     * @return Datapoint
     */
    public function setSampleCount(string $sampleCount): Datapoint
    {
        $this->sampleCount = $sampleCount;
        return $this;
    }

    /**
     * @return string
     */
    public function getSum(): string
    {
        return $this->sum;
    }

    /**
     * @param string $sum
     * @return Datapoint
     */
    public function setSum(string $sum): Datapoint
    {
        $this->sum = $sum;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getTimestamp(): DateTime
    {
        return $this->timestamp;
    }

    /**
     * @param DateTime $timestamp
     * @return Datapoint
     */
    public function setTimestamp(DateTime $timestamp): Datapoint
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    /**
     * @return string
     */
    public function getUnit(): string
    {
        return $this->unit;
    }

    /**
     * @param string $unit
     * @return Datapoint
     */
    public function setUnit(string $unit): Datapoint
    {
        $this->unit = $unit;
        return $this;
    }
}
