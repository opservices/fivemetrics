<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 05/06/17
 * Time: 12:53
 */

namespace DatabaseBundle\Tests\NoSql\QueryBuilder;

use DatabaseBundle\Collection\NoSql\QueryBuilder\ColumnCollection;
use DatabaseBundle\Collection\NoSql\QueryBuilder\ConditionCollection;
use DatabaseBundle\Collection\NoSql\QueryBuilder\FilterCollection;
use DatabaseBundle\NoSql\QueryBuilder\Column;
use DatabaseBundle\NoSql\QueryBuilder\Condition;
use DatabaseBundle\NoSql\QueryBuilder\Functions\Fill;
use DatabaseBundle\NoSql\QueryBuilder\Operators\LogicalAnd;
use DatabaseBundle\NoSql\QueryBuilder\Operators\LogicalOr;
use DatabaseBundle\NoSql\QueryBuilder\Query;
use DatabaseBundle\NoSql\QueryBuilder\Statement\From;
use DatabaseBundle\NoSql\QueryBuilder\Statement\GroupBy;
use DatabaseBundle\NoSql\QueryBuilder\Statement\Limit;
use DatabaseBundle\NoSql\QueryBuilder\Statement\OrderBy;
use DatabaseBundle\NoSql\QueryBuilder\Statement\Select;
use DatabaseBundle\NoSql\QueryBuilder\Statement\Where;
use PHPUnit\Framework\TestCase;

/**
 * Class QueryTest
 * @package DatabaseBundle\Tests\NoSql\QueryBuilder
 */
class QueryTest extends TestCase
{
    /**
     * @test
     */
    public function getFullValidQuery()
    {
        $filterCollection = new FilterCollection(new LogicalAnd());

        $filterCollection->add(new ConditionCollection(new LogicalOr()));
        $filterCollection->last()->add(new Condition('a', '=', 'b'));
        $filterCollection->last()->add(new Condition('a', '=', 'c'));

        $filterCollection->add(new ConditionCollection(new LogicalOr()));
        $filterCollection->last()->add(new Condition('b', '=', 'c'));
        $filterCollection->last()->add(new Condition('b', '=', 'd'));

        $query = new Query(
            new Select(new ColumnCollection([ new Column('value') ])),
            new From('test'),
            new Where($filterCollection),
            new GroupBy('hour'),
            new OrderBy('newest'),
            new Fill(0),
            new Limit(10)
        );

        $expected =
            'SELECT "value"'
            . ' FROM "test"'
            . ' WHERE ('
            . '(("a" = \'b\') OR ("a" = \'c\')) AND (("b" = \'c\') OR ("b" = \'d\')))'
            . ' GROUP BY TIME(1h) ORDER BY "time" DESC FILL(0) LIMIT 10';

        $this->assertEquals(
            $expected,
            (string)$query
        );
    }
}
