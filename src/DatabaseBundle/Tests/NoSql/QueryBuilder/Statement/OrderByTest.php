<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 05/06/17
 * Time: 14:05
 */

namespace DatabaseBundle\Tests\NoSql\QueryBuilder\Statement;

use DatabaseBundle\NoSql\QueryBuilder\Statement\OrderBy;
use PHPUnit\Framework\TestCase;

/**
 * Class OrderByTest
 * @package DatabaseBundle\Tests\NoSql\QueryBuilder\Statement
 */
class OrderByTest extends TestCase
{
    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function instantiateOrderByWithInvalidOrder()
    {
        new OrderBy('sssss');
    }

    /**
     * @test
     */
    public function getDefaultOrderBy()
    {
        $orderBy = new OrderBy();
        $this->assertEquals('ORDER BY "time" DESC', (string)$orderBy);
    }
}
