<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 22/02/17
 * Time: 12:50
 */

namespace DataSourceBundle\Tests\Entity\Aws\CloudWatch;

use DataSourceBundle\Entity\Aws\CloudWatch\Datapoint;
use EssentialsBundle\Entity\DateTime\DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Class DatapointTest
 * @package Test\Entity\Aws\CloudWatch
 */
class DatapointTest extends TestCase
{
    /**
     * @var Datapoint
     */
    protected $dp;

    public function setUp()
    {
        $this->dp = new Datapoint(
            "10",
            "1",
            "2",
            "3",
            "10",
            DateTime::createFromFormat('Y-m-d H:i', '2017-02-22 12:54'),
            "Count",
            "label",
            ["test"]
        );
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals(
            "10",
            $this->dp->getAverage()
        );

        $this->assertEquals(
            "1",
            $this->dp->getMaximum()
        );

        $this->assertEquals(
            "2",
            $this->dp->getMinimum()
        );

        $this->assertEquals(
            "3",
            $this->dp->getSampleCount()
        );

        $this->assertEquals(
            "10",
            $this->dp->getSum()
        );

        $this->assertEquals(
            '2017-02-22 12:54',
            $this->dp->getTimestamp()->format('Y-m-d H:i')
        );

        $this->assertEquals(
            'Count',
            $this->dp->getUnit()
        );

        $this->assertEquals(
            'label',
            $this->dp->getLabel()
        );

        $this->assertEquals(
            [ 'test' ],
            $this->dp->getExtendedStatistics()
        );
    }

    /**
     * @test
     */
    public function setAverage()
    {
        $this->dp->setAverage("11");

        $this->assertEquals(
            "11",
            $this->dp->getAverage()
        );
    }

    /**
     * @test
     */
    public function setMaximum()
    {
        $this->dp->setMaximum("2");

        $this->assertEquals(
            "2",
            $this->dp->getMaximum()
        );
    }

    /**
     * @test
     */
    public function setMinimum()
    {
        $this->dp->setMinimum("3");

        $this->assertEquals(
            "3",
            $this->dp->getMinimum()
        );
    }

    /**
     * @test
     */
    public function setSampleCount()
    {
        $this->dp->setSampleCount("4");

        $this->assertEquals(
            "4",
            $this->dp->getSampleCount()
        );
    }

    /**
     * @test
     */
    public function setSum()
    {
        $this->dp->setSum("11");

        $this->assertEquals(
            "11",
            $this->dp->getSum()
        );
    }

    /**
     * @test
     */
    public function setTimestamp()
    {
        $this->dp->setTimestamp(
            DateTime::createFromFormat('Y-m-d H:i', '2017-02-22 13:14')
        );

        $this->assertEquals(
            '2017-02-22 13:14',
            $this->dp->getTimestamp()->format('Y-m-d H:i')
        );
    }

    /**
     * @test
     */
    public function setUnit()
    {
        $this->dp->setUnit('MB');

        $this->assertEquals(
            'MB',
            $this->dp->getUnit()
        );
    }

    /**
     * @test
     */
    public function setLabel()
    {
        $this->dp->setLabel('label.test');

        $this->assertEquals(
            'label.test',
            $this->dp->getLabel()
        );
    }

    /**
     * @test
     */
    public function setExtendedStatistics()
    {
        $this->dp->setExtendedStatistics(['test.unit']);

        $this->assertEquals(
            ['test.unit'],
            $this->dp->getExtendedStatistics()
        );
    }
}
