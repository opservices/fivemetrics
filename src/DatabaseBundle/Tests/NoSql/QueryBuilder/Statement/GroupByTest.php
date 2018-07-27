<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 05/06/17
 * Time: 14:30
 */

namespace DatabaseBundle\Tests\NoSql\QueryBuilder\Statement;

use DatabaseBundle\NoSql\QueryBuilder\Statement\GroupBy;
use PHPUnit\Framework\TestCase;

/**
 * Class GroupByTest
 * @package DatabaseBundle\Tests\NoSql\QueryBuilder\Statement
 */
class GroupByTest extends TestCase
{
    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function tryGetInvalidGroupByInstance()
    {
        new GroupBy();
    }

    /**
     * @test
     */
    public function groupByTimeAndTags()
    {
        $this->assertEquals(
            'GROUP BY TIME(1h), "unit", "test"',
            (string)new GroupBy('hour', [ 'unit', 'test' ])
        );
    }
}
