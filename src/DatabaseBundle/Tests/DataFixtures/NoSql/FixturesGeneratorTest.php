<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 11/08/17
 * Time: 15:52
 */

namespace DatabaseBundle\Tests\DataFixtures\NoSql;

use DatabaseBundle\DataFixtures\NoSql\Configuration\Builder;
use DatabaseBundle\DataFixtures\NoSql\FixturesGenerator;
use EssentialsBundle\Collection\Metric\MetricCollection;
use PHPUnit\Framework\TestCase;

class FixturesGeneratorTest extends TestCase
{
    /**
     * @test
     * @dataProvider fixturesConfigurationProvider
     */
    public function generateSeriesFromConfiguration(array $conf)
    {
        $metrics = FixturesGenerator::generateSeries(Builder::factory($conf));
        $this->assertCount(
            $conf['series'][0]['total'],
            $metrics
        );
    }

    public function fixturesConfigurationProvider()
    {
        return [
            [json_decode('
                {
                  "series": [
                    {
                      "name": "test.test",
                      "interval": 300,
                      "total": 10,
                      "tags": [
                        {
                          "key": "test",
                          "value": {
                            "type": "fixed",
                            "data": "unit"
                          }
                        }
                      ],
                      "point": {
                        "value": {
                          "type": "random",
                          "data": [ 1, 2, 3 ]
                        },
                        "minimum": {
                          "type": "random",
                          "data": {
                            "range": {
                              "min": 1,
                              "max": 10
                            }
                          }
                        },
                        "maximum": {
                          "type": "fixed",
                          "data": 1000
                        },
                        "sampleCount": {
                          "type": "fixed",
                          "data": 1
                        }
                      }
                    }
                  ]
                }
            ', true)]
        ];
    }
}
