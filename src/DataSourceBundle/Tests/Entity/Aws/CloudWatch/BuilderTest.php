<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 16/02/17
 * Time: 17:38
 */

namespace DataSourceBundle\Tests\Entity\Aws\CloudWatch;

use DataSourceBundle\Collection\Aws\CloudWatch\DimensionCollection;
use DataSourceBundle\Entity\Aws\CloudWatch\Builder;
use DataSourceBundle\Entity\Aws\CloudWatch\Dimension;
use DataSourceBundle\Entity\Aws\CloudWatch\MetricStatistic;
use EssentialsBundle\Entity\DateTime\DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Class BuilderTest
 * @package DataSourceBundle\Test\Entity\Aws\CloudWatch
 */
class BuilderTest extends TestCase
{
    /**
     * @test
     * @dataProvider validAwsMetricsData
     * @param $data
     */
    public function buildAwsMetric($data)
    {
        $metrics = Builder::buildAwsMetrics([$data]);

        $this->assertInstanceOf(
            'DataSourceBundle\Collection\Aws\CloudWatch\MetricCollection',
            $metrics
        );

        $this->assertEquals(
            json_encode($data),
            json_encode($metrics->current())
        );
    }

    /**
     * @test
     * @dataProvider validAwsMetricsData
     * @param $data
     */
    public function buildMetricStatistics($data)
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i', '2017-02-16 17:54');

        $metrics = Builder::buildMetricStatistics(
            [$data],
            $dt
        );

        $this->assertInstanceOf(
            'DataSourceBundle\Collection\Aws\CloudWatch\MetricStatisticCollection',
            $metrics
        );

        $this->assertGreaterThan(0, count($metrics));
    }

    /**
     * @test
     * @dataProvider validAwsMetricsData
     * @param $data
     */
    public function buildMetricStatisticsInvalidPeriod($data)
    {
        $dt = DateTime::createFromFormat('Y-m-d H:i', '2017-02-16 17:54');

        $metrics = Builder::buildMetricStatistics(
            [$data],
            $dt,
            'test'
        );

        $this->assertInstanceOf(
            'DataSourceBundle\Collection\Aws\CloudWatch\MetricStatisticCollection',
            $metrics
        );

        $this->assertGreaterThan(0, count($metrics));
    }

    public function validAwsMetricsData()
    {
        $metrics = [
            '{
                "Namespace": "AWS/EC2",
                "MetricName": "test",
                "Dimensions": [
                    {
                        "Name": "Name",
                        "Value": "Value"
                    }
                ]
            }'
        ];

        foreach ($metrics as $metric) {
            yield [ json_decode($metric, true) ];
        }
    }

    /**
     * @test
     */
    public function buildMetric()
    {
        $dims = new DimensionCollection();
        $dims->add(new Dimension('name', 'value'));

        $metric = new MetricStatistic(
            'AWS/EC2',
            'metricName',
            $dims,
            1487274000,
            1487274714,
            60,
            [ 'Sum' ],
            'Megabytes'
        );

        $metrics = Builder::buildMetrics(
            $metric,
            [[ 'average' => 10]]
        );

        $this->assertInstanceOf(
            'EssentialsBundle\Collection\Metric\MetricCollection',
            $metrics
        );

        $this->assertGreaterThan(
            0,
            count($metrics->current())
        );
    }
}
