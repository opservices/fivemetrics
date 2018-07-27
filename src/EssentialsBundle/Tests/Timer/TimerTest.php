<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 07/12/17
 * Time: 14:25
 */

namespace EssentialsBundle\Tests\Timer;

use EssentialsBundle\Timer\Timer;
use PHPUnit\Framework\TestCase;

class TimerTest extends TestCase
{
    /**
     * @test
     */
    public function construct()
    {
        $timer = new Timer(0.0);
        $this->assertEquals(0.0, $timer->getStartTime());
    }

    /**
     * @test
     */
    public function resetTimersWithoutStart()
    {
        $timer = new Timer(0.0);
        $this->assertEquals(0.0, $timer->getStartTime());
        /** @var Timer $timer */
        $timer = unserialize(serialize($timer));
        $this->assertNotNull($timer->getTimeFromUnserialization());
        $this->assertNotNull($timer->getTimeFromSerialization());

        $timer->resetTimers();

        $this->assertEquals(0.0, $timer->getStartTime());
        $this->assertNull($timer->getTimeFromUnserialization());
        $this->assertNull($timer->getTimeFromSerialization());
    }

    /**
     * @test
     */
    public function resetTimersWithStart()
    {
        $timer = new Timer(0.0);
        $this->assertEquals(0.0, $timer->getStartTime());
        /** @var Timer $timer */
        $timer = unserialize(serialize($timer));
        $this->assertNotNull($timer->getTimeFromUnserialization());
        $this->assertNotNull($timer->getTimeFromSerialization());

        $timer->resetTimers(true);

        $this->assertGreaterThan(0.0, $timer->getStartTime());
        $this->assertNull($timer->getTimeFromUnserialization());
        $this->assertNull($timer->getTimeFromSerialization());
    }

    /**
     * @test
     */
    public function jsonSerialize()
    {
        $timer = new Timer();
        $timer = unserialize(serialize($timer));
        $timer = json_decode(json_encode($timer), true);

        $this->assertArrayHasKey('start', $timer);
        $this->assertGreaterThan(0, $timer['start']);

        $this->assertArrayHasKey('elapsedTime', $timer);
        $this->assertGreaterThan(0, $timer['elapsedTime']);

        $this->assertArrayHasKey('serialized', $timer);
        $this->assertGreaterThan(0, $timer['serialized']);

        $this->assertArrayHasKey('unserialized', $timer);
        $this->assertGreaterThan(0, $timer['unserialized']);

        $this->assertArrayHasKey('death', $timer);

        $this->assertArrayHasKey('timeUntilSerialization', $timer);
        $this->assertGreaterThan(0, $timer['timeUntilSerialization']);

        $this->assertArrayHasKey('timeFromSerialization', $timer);
        $this->assertGreaterThan(0, $timer['timeFromSerialization']);

        $this->assertArrayHasKey('timeUntilUnserialization', $timer);
        $this->assertGreaterThan(0, $timer['timeUntilUnserialization']);

        $this->assertArrayHasKey('timeFromUnserialization', $timer);
        $this->assertGreaterThan(0, $timer['timeFromUnserialization']);
    }

    /**
     * @test
     */
    public function resume()
    {
        $timer = new Timer();

        $timer->pause();
        $time = $timer->getTime();

        usleep(100);

        $this->assertEquals($time, $timer->getTime());

        $timer->resume();
        $this->assertGreaterThan($time, $timer->getTime());
    }
}
