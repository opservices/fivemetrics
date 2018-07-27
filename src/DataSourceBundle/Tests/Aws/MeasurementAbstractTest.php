<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 22/02/17
 * Time: 10:14
 */

namespace DataSourceBundle\Tests\Aws;

use EssentialsBundle\Collection\Metric\MetricCollection;
use DataSourceBundle\Aws\MeasurementAbstract;
use DataSourceBundle\Entity\Aws\Region\California;
use DataSourceBundle\Entity\Aws\Region\SaoPaulo;
use EssentialsBundle\Reflection;
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
        $this->measurement = new Measurement(new California());
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertInstanceOf(
            'DataSourceBundle\Entity\Aws\Region\California',
            $this->measurement->getRegion()
        );
    }

    /**
     * @test
     */
    public function setRegion()
    {
        $this->measurement->setRegion(new SaoPaulo());

        $this->assertInstanceOf(
            'DataSourceBundle\Entity\Aws\Region\SaoPaulo',
            $this->measurement->getRegion()
        );
    }

    /**
     * @test
     */
    public function getNameParts()
    {
        $this->assertEquals(
            [ 'aws' ],
            Reflection::callMethodOnObject($this->measurement, 'getNameParts')
        );
    }
}
