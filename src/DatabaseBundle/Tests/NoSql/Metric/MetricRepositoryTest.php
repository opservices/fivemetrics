<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 05/06/17
 * Time: 17:36
 */

namespace DatabaseBundle\Tests\NoSql\Metric;

use DatabaseBundle\NoSql\DatabaseConnectionProvider;
use DatabaseBundle\NoSql\Metric\MetricRepository;
use DatabaseBundle\NoSql\QueryBuilder\QueryBuilder;
use EssentialsBundle\Collection\Metric\MetricCollection;
use EssentialsBundle\Collection\Metric\PointCollection;
use EssentialsBundle\Collection\Tag\TagCollection;
use EssentialsBundle\Entity\Metric\Metric;
use EssentialsBundle\Entity\Metric\Point;
use EssentialsBundle\Reflection;
use InfluxDB\Database;
use PHPUnit\Framework\TestCase;

/**
 * Class MetricRepositoryTest
 * @package DatabaseBundle\Tests\NoSql\Metric
 */
class MetricRepositoryTest extends TestCase
{
    /**
     * @test
     * @dataProvider seriesProvider
     */
    public function getHistory(array $series)
    {
        $metricRepo = $this->getMockBuilder('DatabaseBundle\NoSql\Metric\MetricRepository')
            ->disableOriginalConstructor()
            ->setMethods([ 'getInfluxSeriesData' ])
            ->getMock();

        $metricRepo->expects($this->once())
            ->method('getInfluxSeriesData')
            ->will($this->returnValue($series));

        $result = $metricRepo->getHistory('database', 'metric.name', []);

        $this->assertArrayHasKey('series', $result);

        foreach ($result['series'] as $serie) {
            $this->assertArrayHasKey('name', $serie);
            $this->assertArrayHasKey('tags', $serie);
            $this->assertArrayHasKey('points', $serie);
            $this->assertArrayHasKey('minimum', $serie);
            $this->assertArrayHasKey('maximum', $serie);
            foreach ($serie['points'] as $point) {
                $this->assertArrayHasKey('time', $point);
                $this->assertArrayHasKey('value', $point);
            }
        }
    }

    public function seriesProvider()
    {
        return [
            [ json_decode('[
                  {
                    "name": "aws.ec2.instanceState",
                    "columns": [
                      "time",
                      "region",
                      "availabilityZone",
                      "value",
                      "minimum",
                      "maximum"
                    ],
                    "values": [
                      [
                        "2017-06-05T12:22:40Z",
                        "sa-east-1",
                        "sa-east-1a",
                        2,
                        0,
                        0
                      ],
                      [
                        "2017-06-05T12:22:42Z",
                        "us-east-1",
                        "us-east-1a",
                        1,
                        0,
                        0
                      ]
                    ]
                  }
                ]', true) ],
            [ json_decode('[
                  {
                    "name": "aws.ec2.instanceState",
                    "columns": [
                      "time",
                      "value"
                    ],
                    "values": [
                      [
                        "2017-06-05T12:00:00Z",
                        3
                      ],
                      [
                        "2017-06-05T13:00:00Z",
                        3
                      ]
                    ]
                  }
                ]', true) ],
            [ json_decode('[
                  {
                    "name": "aws.ec2.instanceState",
                    "columns": [
                      "time",
                      "value"
                    ],
                    "values": [
                      [
                        "2017-06-05T12:00:00Z",
                        null
                      ],
                      [
                        "2017-06-06T11:00:00Z",
                        75
                      ]
                    ]
                  }
                ]', true) ],
            [ json_decode('[
                  {
                    "name": "aws.s3.bucket",
                    "columns": [
                      "time",
                      "value"
                    ],
                    "values": [
                      [
                        "2017-06-05T12:00:00Z",
                        null
                      ],
                      [
                        "2017-06-06T11:00:00Z",
                        96
                      ]
                    ]
                  }
                ]', true) ],
            [ json_decode('[
                  {
                    "name": "aws.s3.bucket.tag",
                    "columns": [
                      "time",
                      "value",
                      "foo"
                    ],
                    "values": [
                      [
                        "2017-06-05T12:00:00Z",
                        85,
                        "bar"
                      ],
                      [
                        "2017-06-06T11:00:00Z",
                        13,
                        "tag"
                      ],
                      [
                        "2017-06-06T11:00:00Z",
                        2,
                        "othertag"
                      ]
                    ]
                  }
                ]', true) ],
            [ json_decode('[
                  {
                    "name": "aws.ec2.instanceType",
                    "tags": {
                      "region": "us-east-1",
                      "type": "c3.2xlarge"
                    },
                    "columns": [
                      "time",
                      "value"
                    ],
                    "values": [
                      [
                        "2017-06-05T12:00:00Z",
                        null
                      ],
                      [
                        "2017-06-06T11:00:00Z",
                        1
                      ]
                    ]
                  },
                  {
                    "name": "aws.ec2.instanceType",
                    "tags": {
                      "region": "us-east-1",
                      "type": "c3.large"
                    },
                    "columns": [
                      "time",
                      "value"
                    ],
                    "values": [
                      [
                        "2017-06-05T12:00:00Z",
                        null
                      ],
                      [
                        "2017-06-06T11:00:00Z",
                        10
                      ]
                    ]
                  },
                  {
                    "name": "aws.ec2.instanceType",
                    "tags": {
                      "region": "us-east-1",
                      "type": "m3.medium"
                    },
                    "columns": [
                      "time",
                      "value"
                    ],
                    "values": [
                      [
                        "2017-06-05T12:00:00Z",
                        null
                      ],
                      [
                        "2017-06-06T11:00:00Z",
                        12
                      ]
                    ]
                  }
                ]', true) ],
        ];
    }

    /**
     * @test
     */
    public function getInfluxQlWithCustomQueryBuilder()
    {
        $params = [ 'period' => 'last5minutes', 'query' => [] ];

        $sql = Reflection::callMethodOnObject(
            new MetricRepository(new QueryBuilder()),
            'getInfluxQL',
            [ 'test.unit', $params ]
        );

        $this->assertRegexp(
            '/SELECT "value" FROM "test.unit" WHERE \(\("time" > [0-9]+\) AND \("time" < [0-9]+\)\)    LIMIT 1000/',
            $sql
        );
    }

    /**
     * @test
     */
    public function getInfluxQl()
    {
        $params = [ 'period' => 'last5minutes', 'query' => [] ];

        $sql = Reflection::callMethodOnObject(
            new MetricRepository(),
            'getInfluxQL',
            [ 'test.unit', $params ]
        );

        $this->assertRegexp(
            '/SELECT "value" FROM "test.unit" WHERE \(\("time" > [0-9]+\) AND \("time" < [0-9]+\)\)    LIMIT 1000/',
            $sql
        );
    }

    /**
     * @test
     */
    public function putMetrics()
    {
        $db = $this
            ->getMockBuilder(Database::class)
            ->disableOriginalConstructor()
            ->setMethods([ 'writePoints' ])
            ->getMock();

        $db->expects($this->any())
            ->method('writePoints')
            ->will($this->returnValue(null));

        $connectionProvider = $this
            ->getMockBuilder(DatabaseConnectionProvider::class)
            ->disableOriginalConstructor()
            ->setMethods([ 'getConnection' ])
            ->getMock();

        $connectionProvider->expects($this->any())
            ->method('getConnection')
            ->will($this->returnValue($db));

        $metrics = new MetricCollection([ new Metric(
            'test.unit',
            new TagCollection(),
            new PointCollection([ new Point(10) ])
        ) ]);

        $repository = new MetricRepository(
            new QueryBuilder(),
            $connectionProvider
        );

        $this->assertInstanceOf(
            MetricRepository::class,
            $repository->putMetrics('test', $metrics)
        );
    }
}
