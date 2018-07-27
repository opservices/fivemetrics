<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 16/02/17
 * Time: 13:41
 */

namespace DataSourceBundle\Tests\Entity\Aws\AutoScaling;

use DataSourceBundle\Entity\Aws\AutoScaling\EnabledMetric;
use PHPUnit\Framework\TestCase;

/**
 * Class EnabledMetricTest
 * @package DataSourceBundle\Test\Entity\Aws\AutoScaling
 */
class EnabledMetricTest extends TestCase
{
    /**
     * @var EnabledMetric
     */
    protected $metric;

    public function setUp()
    {
        $this->metric = new EnabledMetric(
            "granularity",
            "metric"
        );
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals(
            "granularity",
            $this->metric->getGranularity()
        );

        $this->assertEquals(
            "metric",
            $this->metric->getMetric()
        );
    }

    /**
     * @test
     */
    public function setGranularity()
    {
        $this->metric->setGranularity("granularity.test");

        $this->assertEquals(
            "granularity.test",
            $this->metric->getGranularity()
        );
    }

    /**
     * @test
     */
    public function setMetric()
    {
        $this->metric->setMetric("metric.test");

        $this->assertEquals(
            "metric.test",
            $this->metric->getMetric()
        );
    }
}
