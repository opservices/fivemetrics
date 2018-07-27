<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 11/08/17
 * Time: 15:03
 */

namespace DatabaseBundle\Tests\DataFixtures\NoSql\Configuration;

use DatabaseBundle\DataFixtures\NoSql\Configuration\Point;
use DatabaseBundle\DataFixtures\NoSql\Configuration\Value;
use PHPUnit\Framework\TestCase;

class PointTest extends TestCase
{
    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $range = [ 'range' => [ 'min' => 0, 'max' => 10 ]];
        $list  = [ 1, 2, 3 ];

        $p = new Point(
            new Value('fixed', 10),
            new Value('random', $list),
            new Value('random', $range),
            new Value('fixed', '1'),
            new Value('fixed', 0)
        );

        $this->assertInstanceOf(Value::class, $p->getValue());
        $this->assertInstanceOf(Value::class, $p->getSum());
        $this->assertInstanceOf(Value::class, $p->getSampleCount());
        $this->assertInstanceOf(Value::class, $p->getMinimum());
        $this->assertInstanceOf(Value::class, $p->getMaximum());

        $this->assertEquals('fixed', $p->getValue()->getType());
        $this->assertEquals('random', $p->getMinimum()->getType());
        $this->assertEquals('random', $p->getMaximum()->getType());
        $this->assertEquals('fixed', $p->getSampleCount()->getType());
        $this->assertEquals('fixed', $p->getSum()->getType());

        $this->assertEquals(10, $p->getValue()->getData());
        $this->assertEquals($list, $p->getMinimum()->getData());
        $this->assertEquals($range, $p->getMaximum()->getData());
        $this->assertEquals('1', $p->getSampleCount()->getData());
        $this->assertEquals(0, $p->getSum()->getData());
    }

    /**
     * @test
     */
    public function constructPointConfigurationOnlyWithValue()
    {
        $p = new Point(
            new Value('fixed', 10)
        );

        $this->assertInstanceOf(Value::class, $p->getValue());
        $this->assertNull($p->getSum());
        $this->assertNull($p->getSampleCount());
        $this->assertNull($p->getMinimum());
        $this->assertNull($p->getMaximum());
    }
}
