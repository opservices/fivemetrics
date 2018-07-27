<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 05/06/17
 * Time: 15:07
 */

namespace DatabaseBundle\Tests\NoSql\QueryBuilder;

use DatabaseBundle\NoSql\QueryBuilder\QueryBuilder;
use PHPUnit\Framework\TestCase;

/**
 * Class QueryBuilderTest
 * @package DatabaseBundle\Tests\NoSql\QueryBuilder
 */
class QueryBuilderTest extends TestCase
{
    /**
     * @test
     * @dataProvider validQueriesProvider
     */
    public function getQuery(array $query, string $series)
    {
        $builder = new QueryBuilder();

        $this->assertInstanceOf(
            'DatabaseBundle\NoSql\QueryBuilder\Query',
            $builder->getQuery($query, $series)
        );
    }

    public function validQueriesProvider()
    {
        return [
            [
                [
                    'period' => 'last24hours',
                    'query' => [
                        'aggregation' => 'sum',
                        'groupBy' => [
                            'time' => 'hour',
                        ],
                        'limit' => 100,
                        'query' => [
                            'aggregation' => 'max',
                            'groupBy' => [
                                'time' => 'hour',
                                'tags' => [
                                    'region',
                                    'availabilityZone',
                                ],
                            ],
                            'filter' => [
                                'state' => [
                                    'running',
                                ],
                            ],
                        ],
                    ],
                ],
                'unit.test',
            ],
            [
                [
                    'period' => 'last24hours',
                    'query' => [
                        'limit' => 100,
                        'filter' => [
                            'state' => [
                                'running',
                            ],
                        ],
                        'columns' => [
                            'a',
                            'b',
                        ],
                    ],
                ],
                'unit.test',
            ],
            [
                [
                    'period' => 'last24hours',
                    'query' => [],
                ],
                'unit.test',
            ],
            [
                [
                    'period' => 'last24hours',
                    'query' => [
                        'orderBy' => 'newest',
                    ]
                ],
                'unit.test',
            ],
            [
                [
                    'period' => 'last5minutes',
                    'query' => [],
                ],
                'unit.test',
            ],
            [
                [
                    'period' => 'last5minutes',
                    'query' => [
                        'filter' => [
                            'state' => [
                                'running',
                            ],
                        ],
                    ],
                ],
                'unit.test',
            ],
        ];
    }
}
