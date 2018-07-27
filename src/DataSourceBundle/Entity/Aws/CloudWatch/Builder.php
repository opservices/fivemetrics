<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 10/02/17
 * Time: 11:05
 */

namespace DataSourceBundle\Entity\Aws\CloudWatch;

use DataSourceBundle\Collection\Aws\CloudWatch\DimensionCollection;
use DataSourceBundle\Collection\Aws\CloudWatch\MetricStatisticCollection;
use DataSourceBundle\Collection\Aws\CloudWatch\MetricCollection as AwsMetricCollection;
use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\Entity\Metric\Builder as MetricBuilder;
use EssentialsBundle\Collection\Metric\MetricCollection;

/**
 * Class Builder
 * @package Entity\Aws\CloudWatch
 */
class Builder
{
    const STATISTIC_TIME_PERIOD = [
        'lastest' => [
            // 5 minutes
            'startTimeDiff' => 60 * 5,
            'endTimeDIff'   => 0,
            'period'        => 60
        ],
        'last24hours' => [
            // 1 day
            'startTimeDiff' => 60 * 60 * 24,
            'endTimeDIff'   => 0,
            'period'        => 60
        ]
    ];

    /**
     * @param array $data
     * @param DateTime $endTime
     * @param string $timeperiod
     * @return MetricStatisticCollection
     */
    public static function buildMetricStatistics(
        array $data,
        DateTime $endTime,
        string $timeperiod = 'lastest'
    ): MetricStatisticCollection {
        $metricStatistics = new MetricStatisticCollection();

        $key = (empty(self::STATISTIC_TIME_PERIOD[$timeperiod]))
            ? 'lastest'
            : $timeperiod;

        $timestamp = $endTime->getTimestamp();

        foreach ($data as $metricStatistic) {
            $metricStatistics->add(
                new MetricStatistic(
                    $metricStatistic['Namespace'],
                    $metricStatistic['MetricName'],
                    self::buildDimensions($metricStatistic['Dimensions']),
                    $timestamp - self::STATISTIC_TIME_PERIOD[$key]['startTimeDiff'],
                    $timestamp - self::STATISTIC_TIME_PERIOD[$key]['endTimeDiff'],
                    self::STATISTIC_TIME_PERIOD[$key]['period'],
                    MetricStatistic::STATISTIC_TYPES
                )
            );
        }

        return $metricStatistics;
    }

    /**
     * @param MetricStatistic $metricStatistic
     * @param array $data
     * @return MetricCollection
     */
    public static function buildMetrics(
        MetricStatistic $metricStatistic,
        array $data
    ): MetricCollection {

        $name = sprintf(
            'aws.cloudwatch.%s.%s',
            $metricStatistic->getServiceName(),
            $metricStatistic->getMetricName()
        );

         return MetricBuilder::build([
             [
                 'name' => $name,
                 'tags' => self::buildMetricTagData($metricStatistic),
                 'points' => self::buildMetricPoints($data)
             ]
         ]);
    }

    /**
     * @param array $data
     * @return array
     */
    protected static function buildMetricPoints(array $data): array
    {
        return array_map(function ($point) {
            $newPoint = [];

            foreach ($point as $key => $value) {
                $newPoint[lcfirst($key)] = $value;
            }

            $newPoint['value'] = $newPoint['average'];

            return $newPoint;
        }, $data);
    }

    /**
     * @param MetricStatistic $metricStatistic
     * @return array
     */
    protected static function buildMetricTagData(
        MetricStatistic $metricStatistic
    ): array {
        $tags = [];

        $dimensions = $metricStatistic->getDimensions();
        foreach ($dimensions as $dimension) {
            $tags[] = [
                'key' => $dimension->getName(),
                'value' => $dimension->getValue()
            ];
        }

        return $tags;
    }

    /**
     * @param array $data
     * @return AwsMetricCollection
     */
    public static function buildAwsMetrics(array $data): AwsMetricCollection
    {
        $metrics = new AwsMetricCollection();

        foreach ($data as $metric) {
            $metrics->add(
                new Metric(
                    $metric['Namespace'],
                    $metric['MetricName'],
                    self::buildDimensions($metric['Dimensions'])
                )
            );
        }

        return $metrics;
    }

    /**
     * @param array $data
     * @return DimensionCollection
     */
    public static function buildDimensions(array $data): DimensionCollection
    {
        $dimensions = new DimensionCollection();

        foreach ($data as $dim) {
            $dimensions->add(
                new Dimension(
                    $dim['Name'],
                    $dim['Value']
                )
            );
        }

        return $dimensions;
    }
}
