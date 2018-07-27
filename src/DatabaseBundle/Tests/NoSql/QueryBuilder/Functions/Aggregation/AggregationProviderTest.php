<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 05/06/17
 * Time: 09:40
 */

namespace DatabaseBundle\Tests\NoSql\QueryBuilder\Functions\Aggregation;

use DatabaseBundle\NoSql\QueryBuilder\Functions\Aggregation\AggregationProvider;
use PHPUnit\Framework\TestCase;

/**
 * Class AggregationProviderTest
 * @package DatabaseBundle\Tests\NoSql\QueryBuilder\Functions\Aggregation
 */
class AggregationProviderTest extends TestCase
{
    /**
     * @test
     * @dataProvider aggregations
     */
    public function getAggregationInstance(
        string $aggregation,
        string $expected
    ) {
        $this->assertEquals(
            $expected,
            (string)AggregationProvider::factory($aggregation)
        );
    }

    public function aggregations()
    {
        return [
            [ 'sum', 'SUM("value") AS "value"' ],
            [ 'SUM', 'SUM("value") AS "value"' ],
            [ 'Sum', 'SUM("value") AS "value"' ],
            [ 'SuM', 'SUM("value") AS "value"' ],
            [ 'max', 'MAX("value") AS "value"' ],
            [ 'Max', 'MAX("value") AS "value"' ],
            [ 'MAX', 'MAX("value") AS "value"' ],
            [ 'mAx', 'MAX("value") AS "value"' ],
            [ 'min', 'MIN("value") AS "value"' ],
            [ 'MIN', 'MIN("value") AS "value"' ],
            [ 'Min', 'MIN("value") AS "value"' ],
            [ 'mIn', 'MIN("value") AS "value"' ],
            [ 'mean', 'MEAN("value") AS "value"' ],
            [ 'MEAN', 'MEAN("value") AS "value"' ],
            [ 'MEAN', 'MEAN("value") AS "value"' ],
            [ 'MeAn', 'MEAN("value") AS "value"' ],
        ];
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function getInvalidAggregationInstance()
    {
        AggregationProvider::factory('test');
    }
}
