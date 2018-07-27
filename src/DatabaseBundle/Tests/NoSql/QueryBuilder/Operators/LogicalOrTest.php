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
use DatabaseBundle\NoSql\QueryBuilder\Operators\LogicalOr;
use PHPUnit\Framework\TestCase;

/**
 * Class LogicalOrTest
 * @package DatabaseBundle\Tests\NoSql\QueryBuilder\Operators
 */
class LogicalOrTest extends TestCase
{
    /**
     * @test
     */
    public function logicalOrToString()
    {
        $conditions = new ConditionCollection(
            new LogicalOr(),
            [
                new Condition('a', '=', 'b'),
                new Condition('c', '=', 'd'),
            ]
        );

        $this->assertEquals(
            '(("a" = \'b\') OR ("c" = \'d\'))',
            (string)$conditions
        );
    }
}
