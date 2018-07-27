<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 05/06/17
 * Time: 14:17
 */

namespace DatabaseBundle\Tests\NoSql\QueryBuilder\Statement;

use DatabaseBundle\Collection\NoSql\QueryBuilder\ColumnCollection;
use DatabaseBundle\NoSql\QueryBuilder\Column;
use DatabaseBundle\NoSql\QueryBuilder\Statement\Select;
use PHPUnit\Framework\TestCase;

/**
 * Class SelectTest
 * @package DatabaseBundle\Tests\NoSql\QueryBuilder\Statement
 */
class SelectTest extends TestCase
{
    /**
     * @test
     */
    public function instantiateValidSelect()
    {
        $columns = new ColumnCollection(
            [ new Column('unit'), new Column('test') ]
        );

        $select = new Select($columns);

        $this->assertEquals(
            'SELECT "unit", "test"',
            (string)$select
        );
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function instantiateInvalidSelect()
    {
        new Select(new ColumnCollection());
    }
}
