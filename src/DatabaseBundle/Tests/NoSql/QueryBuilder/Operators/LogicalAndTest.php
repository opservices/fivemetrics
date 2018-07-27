<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 05/06/17
 * Time: 12:21
 */

namespace DatabaseBundle\Tests\NoSql\QueryBuilder\Operators;

use DatabaseBundle\Collection\NoSql\QueryBuilder\ConditionCollection;
use DatabaseBundle\NoSql\QueryBuilder\Condition;
use DatabaseBundle\NoSql\QueryBuilder\Operators\LogicalAnd;
use PHPUnit\Framework\TestCase;

/**
 * Class LogicalAndTest
 * @package DatabaseBundle\Tests\NoSql\QueryBuilder\Operators
 */
class LogicalAndTest extends TestCase
{
    /**
     * @test
     */
    public function logicalAndToString()
    {
        $conditions = new ConditionCollection(
            new LogicalAnd(),
            [
                new Condition('a', '=', 'b'),
                new Condition('c', '=', 'd'),
            ]
        );

        $this->assertEquals(
            '(("a" = \'b\') AND ("c" = \'d\'))',
            (string)$conditions
        );
    }
}
