<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 11/08/17
 * Time: 14:53
 */

namespace DatabaseBundle\Tests\DataFixtures\NoSql\Configuration;

use DatabaseBundle\DataFixtures\NoSql\Configuration\Builder;
use PHPUnit\Framework\TestCase;

class BuilderTest extends TestCase
{
    /**
     * @test
     * @dataProvider fixturesConfigurationProvider
     */
    public function buildFromValidFixturesConfiguration(array $conf)
    {
        $series = Builder::factory($conf);
        $this->assertCount(2, $series);
        $this->assertEquals("test.test", $series->at(0)->getName());
        $this->assertEquals("test.test2", $series->at(1)->getName());
    }

    public function fixturesConfigurationProvider()
    {
        return [
            [ json_decode('
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
                          "type": "fixed",
                          "data": 1
                        },
                        "minimum": {
                          "type": "fixed",
                          "data": -100
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
                    },
                    {
                      "name": "test.test2",
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
                          "data": {
                            "range": {
                              "min": 10,
                              "max": 20
                            }
                          }
                        },
                        "minimum": {
                          "type": "fixed",
                          "data": -100
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
            ', true) ]
        ];
    }
}
