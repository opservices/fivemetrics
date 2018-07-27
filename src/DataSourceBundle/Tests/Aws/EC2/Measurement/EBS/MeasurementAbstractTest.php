<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 22/02/17
 * Time: 10:14
 */

namespace DataSourceBundle\Aws\EC2\Measurement\EBS;

use EssentialsBundle\Collection\Metric\MetricCollection;
use DataSourceBundle\Entity\Aws\Region\California;
use EssentialsBundle\Reflection;
use DataSourceBundle\Collection\Aws\EBS\Volume\VolumeCollection;
use EssentialsBundle\Entity\DateTime\DateTime;
use PHPUnit\Framework\TestCase;

class Measurement extends MeasurementAbstract
{
    public function getMetrics(): MetricCollection
    {
        return new MetricCollection();
    }
}

/**
 * Class MeasurementAbstractTest
 * @package Test\DataSource\Aws\Common
 */
class MeasurementAbstractTest extends TestCase
{
    /**
     * @var Measurement
     */
    protected $measurement;

    public function setUp()
    {
        $this->measurement = new Measurement(
            new California(),
            new DateTime(),
            new VolumeCollection()
        );
    }

    /**
     * @testdox Should merge tags information in one array
     */
    public function getTags()
    {
        $tags = [["key" => 1, "value" => 2]];
        $awsTags = [["Key" => "mergedKey", "Value" => "mergedValue"]];

        $expected = [["key" => 1, "value" => 2], ["key" => "mergedKey", "value" => "mergedValue"]];

        $actual = Reflection::callMethodOnObject(
            $this->measurement,
            "mergeTags",
            [$tags, $awsTags]
        );

        $this->assertEquals($expected, $actual, "Couldn't merge tags correctly");
    }
}
