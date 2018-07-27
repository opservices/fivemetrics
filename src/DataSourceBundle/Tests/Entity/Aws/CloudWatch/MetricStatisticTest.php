<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 16/02/17
 * Time: 17:15
 */

namespace DataSourceBundle\Tests\Entity\Aws\CloudWatch;

use DataSourceBundle\Collection\Aws\CloudWatch\DimensionCollection;
use DataSourceBundle\Entity\Aws\CloudWatch\Dimension;
use DataSourceBundle\Entity\Aws\CloudWatch\MetricStatistic;
use PHPUnit\Framework\TestCase;

/**
 * Class MetricStatisticTest
 * @package DataSourceBundle\Tests\Entity\Aws\CloudWatch
 */
class MetricStatisticTest extends TestCase
{
    /**
     * @var MetricStatistic
     */
    protected $metricStatistic;

    public function setUp()
    {
        $this->metricStatistic = new MetricStatistic(
            'AWS\servicename',
            "metricName",
            new DimensionCollection(),
            1487272700,
            1487272798,
            60,
            [ 'Sum' ],
            'Megabytes',
            []
        );
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals(
            'AWS\servicename',
            $this->metricStatistic->getNamespace()
        );

        $this->assertEquals(
            "metricName",
            $this->metricStatistic->getMetricName()
        );

        $this->assertInstanceOf(
            'DataSourceBundle\Collection\Aws\CloudWatch\DimensionCollection',
            $this->metricStatistic->getDimensions()
        );

        $this->assertEquals(
            1487272700,
            $this->metricStatistic->getStartTime()
        );

        $this->assertEquals(
            1487272798,
            $this->metricStatistic->getEndTime()
        );

        $this->assertEquals(
            60,
            $this->metricStatistic->getPeriod()
        );

        $this->assertEquals(
            [ 'Sum' ],
            $this->metricStatistic->getStatistics()
        );

        $this->assertEquals(
            'Megabytes',
            $this->metricStatistic->getUnit()
        );

        $this->assertEmpty($this->metricStatistic->getExtendedStatistics());
    }

    /**
     * @test
     */
    public function getServiceName()
    {
        $this->assertEquals(
            'servicename',
            $this->metricStatistic->getServiceName()
        );
    }

    /**
     * @test
     */
    public function setNamespace()
    {
        $this->metricStatistic->setNamespace("namespace.test");

        $this->assertEquals(
            "namespace.test",
            $this->metricStatistic->getNamespace()
        );
    }

    /**
     * @test
     */
    public function setMetricName()
    {
        $this->metricStatistic->setMetricName("metricName.test");

        $this->assertEquals(
            "metricName.test",
            $this->metricStatistic->getMetricName()
        );
    }

    /**
     * @test
     */
    public function setDimensions()
    {
        $dims = new DimensionCollection();
        $dims->add(new Dimension("name", "value"));

        $this->metricStatistic->setDimensions($dims);

        $this->assertEquals(
            $dims,
            $this->metricStatistic->getDimensions()
        );
    }

    /**
     * @test
     */
    public function setStartTime()
    {
        $this->metricStatistic->setStartTime(1487270000);

        $this->assertEquals(
            1487270000,
            $this->metricStatistic->getStartTime()
        );
    }

    /**
     * @test
     */
    public function setEndTime()
    {
        $this->metricStatistic->setEndTime(1487272222);

        $this->assertEquals(
            1487272222,
            $this->metricStatistic->getEndTime()
        );
    }

    /**
     * @test
     */
    public function setPeriod()
    {
        $this->metricStatistic->setPeriod(120);

        $this->assertEquals(
            120,
            $this->metricStatistic->getPeriod()
        );
    }

    /**
     * @test
     */
    public function setStatistics()
    {
        $this->metricStatistic->setStatistics([ 'Sum', 'Average' ]);

        $this->assertEquals(
            [ 'Sum', 'Average' ],
            $this->metricStatistic->getStatistics()
        );
    }

    /**
     * @test
     */
    public function setUnit()
    {
        $this->metricStatistic->setUnit('Kilobytes');

        $this->assertEquals(
            'Kilobytes',
            $this->metricStatistic->getUnit()
        );
    }

    /**
     * @test
     */
    public function setExtendedStatistics()
    {
        $this->metricStatistic->setExtendedStatistics(
            [ 'test' ]
        );

        $this->assertEquals(
            [ 'test' ],
            $this->metricStatistic->getExtendedStatistics()
        );
    }

    /**
     * @test
     * @dataProvider invalidStatistics
     * @expectedException \InvalidArgumentException
     */
    public function setInvalidStatistics($data)
    {
        $this->metricStatistic->setStatistics($data);
    }

    public function invalidStatistics()
    {
        return [
            [
                []
            ],
            [
                [ 'test' ]
            ]
        ];
    }
}
