<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 05/06/17
 * Time: 13:55
 */

namespace DatabaseBundle\Tests\NoSql\QueryBuilder\Statement;

use DatabaseBundle\Collection\NoSql\QueryBuilder\ColumnCollection;
use DatabaseBundle\NoSql\QueryBuilder\Column;
use DatabaseBundle\NoSql\QueryBuilder\Query;
use DatabaseBundle\NoSql\QueryBuilder\Statement\From;
use DatabaseBundle\NoSql\QueryBuilder\Statement\Select;
use PHPUnit\Framework\TestCase;

/**
 * Class FromTest
 * @package DatabaseBundle\Tests\NoSql\QueryBuilder\Statement
 */
class FromTest extends TestCase
{
    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function getFromInstanceUsingAnInvalidTimeSeriesName()
    {
        new From('');
    }

    /**
     * @test
     */
    public function getFromInstanceUsingATimeSeriesName()
    {
        $from = new From('unit.test');
        $this->assertEquals('FROM "unit.test"', (string)$from);
    }

    /**
     * @test
     */
    public function getFromInstanceUsingAQueryInstance()
    {
        $from = new From($this->getQueryInstance());
        $this->assertEquals('FROM (SELECT "value" FROM "test"     )', (string)$from);
    }

    /**
     * @return Query
     */
    public function getQueryInstance()
    {
        return new Query(
            new Select(new ColumnCollection([ new Column('value') ])),
            new From('test')
        );
    }
}
