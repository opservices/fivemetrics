<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 14/02/17
 * Time: 20:30
 */

namespace EssentialsBundle\Tests\Entity\Metric;

use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\Entity\Metric\Point;
use PHPUnit\Framework\TestCase;

/**
 * Class DatapointTest
 * @package EssentialsBundle\Test\Entity\Metric
 */
class PointTest extends TestCase
{
    /**
     * @var Point
     */
    protected $dp;

    public function setUp()
    {
        $this->dp = new Point(10);
    }

    /**
     * @test
     */
    public function defaultDatapointValues()
    {
        $this->assertNull($this->dp->getMaximum());
        $this->assertNull($this->dp->getMinimum());
        $this->assertNull($this->dp->getSampleCount());
        $this->assertNull($this->dp->getSum());
        $this->assertEquals("Count", $this->dp->getUnit());
        $this->assertInstanceOf(
            "EssentialsBundle\\Entity\\DateTime\\DateTime",
            $this->dp->getTime()
        );
    }

    /**
     * @test
     */
    public function createDatapointWithAllOptions()
    {
        $dp = new Point(
            2,
            0,
            10,
            1,
            2,
            DateTime::createFromFormat("Y-m-d H:i:s", "2017-02-14 21:10:30"),
            "MB"
        );

        $this->assertEquals("2", $dp->getValue());
        $this->assertEquals("0", $dp->getMinimum());
        $this->assertEquals("10", $dp->getMaximum());
        $this->assertEquals("1", $dp->getSampleCount());
        $this->assertEquals("2", $dp->getSum());
        $this->assertEquals("2017-02-14 21:10:30", $dp->getTime()->format("Y-m-d H:i:s"));
        $this->assertEquals("MB", $dp->getUnit());
    }
}
