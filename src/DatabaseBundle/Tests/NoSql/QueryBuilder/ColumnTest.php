<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 05/06/17
 * Time: 12:39
 */

namespace DatabaseBundle\Tests\NoSql\QueryBuilder;

use DatabaseBundle\NoSql\QueryBuilder\Column;
use PHPUnit\Framework\TestCase;

/**
 * Class ColumnTest
 * @package DatabaseBundle\Tests\NoSql\QueryBuilder
 */
class ColumnTest extends TestCase
{
    /**
     * @test
     */
    public function validColumnToString()
    {
        $column = new Column("test");
        $this->assertEquals('"test"', (string)$column);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function instantiateEmptyColumn()
    {
        new Column("");
    }
}
