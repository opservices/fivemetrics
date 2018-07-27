<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 05/06/17
 * Time: 13:35
 */

namespace DatabaseBundle\Tests\NoSql\QueryBuilder\Statement;

use DatabaseBundle\Collection\NoSql\QueryBuilder\ConditionCollection;
use DatabaseBundle\Collection\NoSql\QueryBuilder\FilterCollection;
use DatabaseBundle\NoSql\QueryBuilder\Condition;
use DatabaseBundle\NoSql\QueryBuilder\Operators\LogicalAnd;
use DatabaseBundle\NoSql\QueryBuilder\Operators\LogicalOr;
use DatabaseBundle\NoSql\QueryBuilder\Statement\Where;
use PHPUnit\Framework\TestCase;

/**
 * Class WhereTest
 * @package DatabaseBundle\Tests\NoSql\QueryBuilder\Statement
 */
class WhereTest extends TestCase
{
    /**
     * @test
     */
    public function getValidWhereInstance()
    {
        $filterCollection = new FilterCollection(new LogicalAnd());

        $filterCollection->add(new ConditionCollection(new LogicalOr()));
        $filterCollection->last()->add(new Condition('a', '=', 'b'));
        $filterCollection->last()->add(new Condition('a', '=', 'c'));

        $filterCollection->add(new ConditionCollection(new LogicalOr()));
        $filterCollection->last()->add(new Condition('b', '=', 'c'));
        $filterCollection->last()->add(new Condition('b', '=', 'd'));

        $where = new Where($filterCollection);

        $this->assertEquals(
            'WHERE ((("a" = \'b\') OR ("a" = \'c\')) AND (("b" = \'c\') OR ("b" = \'d\')))',
            (string)$where
        );
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function getInvalidWhereInstance()
    {
        new Where(new FilterCollection(new LogicalAnd()));
    }
}
