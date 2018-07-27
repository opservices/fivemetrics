<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 05/06/17
 * Time: 12:09
 */

namespace DatabaseBundle\Tests\NoSql\QueryBuilder\Functions;

use DatabaseBundle\NoSql\QueryBuilder\Functions\Time;
use PHPUnit\Framework\TestCase;

/**
 * Class TimeTest
 * @package DatabaseBundle\Tests\NoSql\QueryBuilder\Functions
 */
class TimeTest extends TestCase
{
    /**
     * @test
     * @dataProvider validTime
     */
    public function instantiateTimeFunction(
        string $interval,
        string $expected
    ) {
        $time = new Time($interval);
        $this->assertEquals(
            $expected,
            (string)$time
        );
    }

    public function validTime()
    {
        return [
            [ 'minute', 'TIME(1m)' ],
            [ 'MINUTE', 'TIME(1m)' ],
            [ 'Minute', 'TIME(1m)' ],
            [ 'hour', 'TIME(1h)' ],
            [ 'HOUR', 'TIME(1h)' ],
            [ 'Hour', 'TIME(1h)' ],
            [ 'day', 'TIME(1d)' ],
            [ 'DAY', 'TIME(1d)' ],
            [ 'Day', 'TIME(1d)' ],
        ];
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function instantiateInvalidTimeInterval()
    {
        new Time('test');
    }
}
