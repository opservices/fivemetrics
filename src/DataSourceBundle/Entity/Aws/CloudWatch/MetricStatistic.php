<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 10/02/17
 * Time: 18:07
 */

namespace DataSourceBundle\Entity\Aws\CloudWatch;

use DataSourceBundle\Collection\Aws\CloudWatch\DimensionCollection;

/**
 * Class MetricStatistic
 * @package DataSourceBundle\Entity\Aws\CloudWatch
 */
class MetricStatistic extends Metric
{
    const UNIT_TYPES = [
        "Seconds",
        "Microseconds",
        "Milliseconds",
        "Bytes",
        "Kilobytes",
        "Megabytes",
        "Gigabytes",
        "Terabytes",
        "Bits",
        "Kilobits",
        "Megabits",
        "Gigabits",
        "Terabits",
        "Percent",
        "Count",
        "Bytes/Second",
        "Kilobytes/Second",
        "Megabytes/Second",
        "Gigabytes/Second",
        "Terabytes/Second",
        "Bits/Second",
        "Kilobits/Second",
        "Megabits/Second",
        "Gigabits/Second",
        "Terabits/Second",
        "Count/Second",
        "None"
    ];

    const STATISTIC_TYPES = [
        'Maximum',
        'Minimum',
        'SampleCount',
        'Sum',
        'Average'
    ];

    /**
     * @var int
     */
    protected $endTime;

    /**
     * @var array
     */
    protected $extendedStatistics;

    /**
     * @var string
     */
    protected $unit = 'None';

    /**
     * @var int
     */
    protected $period;

    /**
     * @var array
     */
    protected $statistics;

    /**
     * @var int
     */
    protected $startTime;

    /**
     * MetricStatistic constructor.
     * @param string $namespace
     * @param string $metricName
     * @param DimensionCollection $dimensions
     * @param int $startTime
     * @param int $endTime
     * @param int $period
     * @param array $statistics
     * @param string|null $unit
     * @param array|null $extendedStatistics
     */
    public function __construct(
        string $namespace,
        string $metricName,
        DimensionCollection $dimensions,
        int $startTime,
        int $endTime,
        int $period,
        array $statistics = null,
        string $unit = null,
        array $extendedStatistics = null
    ) {
        parent::__construct($namespace, $metricName, $dimensions);

        $this->setStartTime($startTime)
            ->setEndTime($endTime)
            ->setPeriod($period)
            ->setStatistics($statistics);

        if (! is_null($unit)) {
            $this->setUnit($unit);
        }

        if (! is_null($extendedStatistics)) {
            $this->setExtendedStatistics($extendedStatistics);
        }
    }

    /**
     * @return string
     */
    public function getServiceName(): string
    {
        return substr($this->getNamespace(), 4);
    }

    /**
     * @return int
     */
    public function getEndTime(): int
    {
        return $this->endTime;
    }

    /**
     * @param int $endTime
     * @return MetricStatistic
     */
    public function setEndTime(int $endTime): MetricStatistic
    {
        $this->endTime = $endTime;
        return $this;
    }

    /**
     * @return array
     */
    public function getExtendedStatistics(): array
    {
        return $this->extendedStatistics;
    }

    /**
     * @param array $extendedStatistics
     * @return MetricStatistic
     */
    public function setExtendedStatistics(array $extendedStatistics): MetricStatistic
    {
        $this->extendedStatistics = $extendedStatistics;
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
     * @return MetricStatistic
     */
    public function setUnit(string $unit): MetricStatistic
    {
        if (! in_array($unit, self::UNIT_TYPES)) {
            throw new \InvalidArgumentException(
                'An invalid metric unit has been provided.'
            );
        }

        $this->unit = $unit;
        return $this;
    }

    /**
     * @return int
     */
    public function getPeriod(): int
    {
        return $this->period;
    }

    /**
     * @param int $period
     * @return MetricStatistic
     */
    public function setPeriod(int $period): MetricStatistic
    {
        $this->period = $period;
        return $this;
    }

    /**
     * @return array
     */
    public function getStatistics(): array
    {
        return $this->statistics;
    }

    /**
     * @param array $statistics
     * @return MetricStatistic
     */
    public function setStatistics(array $statistics): MetricStatistic
    {
        if (empty($statistics)) {
            throw new \InvalidArgumentException("Empty statistics wasn't allowed.");
        }

        foreach ($statistics as $statistic) {
            if (! in_array($statistic, self::STATISTIC_TYPES)) {
                throw new \InvalidArgumentException(
                    'An invalid statistic has been provided: "' . $statistic . '"'
                );
            }
        }

        $this->statistics = $statistics;
        return $this;
    }

    /**
     * @return int
     */
    public function getStartTime(): int
    {
        return $this->startTime;
    }

    /**
     * @param int $startTime
     * @return MetricStatistic
     */
    public function setStartTime(int $startTime): MetricStatistic
    {
        $this->startTime = $startTime;
        return $this;
    }
}
