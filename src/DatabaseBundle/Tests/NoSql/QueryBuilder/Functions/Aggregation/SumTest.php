<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 05/06/17
 * Time: 08:24
 */

namespace DatabaseBundle\Tests\NoSql\QueryBuilder\Functions\Aggregation;

use DatabaseBundle\NoSql\QueryBuilder\Column;
use DatabaseBundle\NoSql\QueryBuilder\Functions\Aggregation\Sum;
use PHPUnit\Framework\TestCase;

/**
 * Class SumTest
 * @package DatabaseBundle\Tests\NoSql\QueryBuilder\Functions\Aggregation
 */
class SumTest extends TestCase
{
    /**
     * @test
     */
    public function toString()
    {
        $aggregation = new Sum(new Column("test"));

        $this->assertEquals(
            'SUM("test") AS "value"',
            (string)$aggregation
        );
    }
}
