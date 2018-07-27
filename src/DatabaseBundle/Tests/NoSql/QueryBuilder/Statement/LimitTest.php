<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 05/06/17
 * Time: 14:05
 */

namespace DatabaseBundle\Tests\NoSql\QueryBuilder\Statement;

use DatabaseBundle\NoSql\QueryBuilder\Statement\Limit;
use PHPUnit\Framework\TestCase;

/**
 * Class LimitTest
 * @package DatabaseBundle\Tests\NoSql\QueryBuilder\Statement
 */
class LimitTest extends TestCase
{
    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @dataProvider invalidLimitValuesProvider
     */
    public function instantiateLimitWithDeniedValues($value)
    {
        new Limit($value);
    }

    public function invalidLimitValuesProvider()
    {
        return [
            [ -1 ],
            [ 0 ],
            [ Limit::MAX + 1 ]
        ];
    }

    /**
     * @test
     */
    public function getLimitInstance()
    {
        $limit = new Limit(10);
        $this->assertEquals('LIMIT 10', $limit);
    }
}
