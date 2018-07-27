<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 19/04/18
 * Time: 16:25
 */

namespace EssentialsBundle\Tests\Entity\Metric;

use EssentialsBundle\Entity\Metric\RealTimeData;

class RealTimeDataTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function getRealTimeKey()
    {
        $realTime = new RealTimeData('unit.test', null, 'suffix');

        $this->assertEquals(
            'realtime-unit.test-suffix',
            $realTime->getRealTimeKey()
        );

        $this->assertEquals(
            'realtime-unit.test',
            $realTime->getReferenceKey()
        );
    }

    /**
     * @test
     */
    public function getData()
    {
        $data = [ 'unit', 'test' ];
        $realTime = new RealTimeData('unit.test', $data);
        $this->assertEquals($data, $realTime->getData());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function setInvalidMetricName()
    {
        new RealTimeData('a b');
    }
}
