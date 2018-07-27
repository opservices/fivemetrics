<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 16/02/17
 * Time: 17:08
 */

namespace DataSourceBundle\Tests\Entity\Aws\CloudWatch;

use DataSourceBundle\Collection\Aws\CloudWatch\DimensionCollection;
use DataSourceBundle\Entity\Aws\CloudWatch\Dimension;
use DataSourceBundle\Entity\Aws\CloudWatch\Metric;
use PHPUnit\Framework\TestCase;

/**
 * Class MetricTest
 * @package DataSourceBundle\Tests\Entity\Aws\CloudWatch
 */
class MetricTest extends TestCase
{
    /**
     * @var Metric
     */
    protected $metric;

    public function setUp()
    {
        $this->metric = new Metric(
            "namespace",
            "metricName",
            new DimensionCollection()
        );
    }

    /**
     * @test
     */
    public function getConstructorParameter()
    {
        $this->assertEquals(
            "namespace",
            $this->metric->getNamespace()
        );

        $this->assertEquals(
            "metricName",
            $this->metric->getMetricName()
        );

        $this->assertInstanceOf(
            'DataSourceBundle\Collection\Aws\CloudWatch\DimensionCollection',
            $this->metric->getDimensions()
        );
    }

    /**
     * @test
     */
    public function setNamespace()
    {
        $this->metric->setNamespace("namespace.test");

        $this->assertEquals(
            "namespace.test",
            $this->metric->getNamespace()
        );
    }

    /**
     * @test
     */
    public function setMetricName()
    {
        $this->metric->setMetricName("metricName.test");

        $this->assertEquals(
            "metricName.test",
            $this->metric->getMetricName()
        );
    }

    /**
     * @test
     */
    public function setDimensions()
    {
        $dims = new DimensionCollection();
        $dims->add(new Dimension("name", "value"));

        $this->metric->setDimensions($dims);

        $this->assertEquals(
            $dims,
            $this->metric->getDimensions()
        );
    }
}
