<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 05/06/17
 * Time: 12:44
 */

namespace DatabaseBundle\Tests\NoSql\QueryBuilder;

use DatabaseBundle\NoSql\QueryBuilder\Condition;
use PHPUnit\Framework\TestCase;

/**
 * Class ConditionTest
 * @package DatabaseBundle\Tests\NoSql\QueryBuilder
 */
class ConditionTest extends TestCase
{
    /**
     * @test
     * @dataProvider validOperatorsProvider
     */
    public function getValidCondition(string $operator)
    {
        $condition = new Condition('a', $operator, 'b');
        $this->assertEquals(
            '("a" ' . $operator . " 'b')",
            (string)$condition
        );
    }

    public function validOperatorsProvider()
    {
        return [
            Condition::OPERATORS
        ];
    }

    /**
     * @test
     * @dataProvider invalidConditionProvider
     * @expectedException \InvalidArgumentException
     */
    public function getInvalidCondition(string $key, string $operator)
    {
        new Condition($key, $operator, 'b');
    }

    public function invalidConditionProvider()
    {
        return [
            [ '' , ''],
            [ 'a' , ''],
            [ '' , '='],
            [ 'a' , 'a'],
        ];
    }
}
