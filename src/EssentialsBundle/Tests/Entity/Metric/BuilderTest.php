<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 14/02/17
 * Time: 21:31
 */

namespace EssentialsBundle\Tests\Entity\Metric;

use EssentialsBundle\Entity\Metric\Builder;
use PHPUnit\Framework\TestCase;

/**
 * Class BuilderTest
 * @package EssentialsBundle\Test\Entity\Metric
 */
class BuilderTest extends TestCase
{
    /**
     * @test
     * @dataProvider validMetricsData
     * @param $data
     */
    public function buildValidMetric($data)
    {
        $metrics = Builder::build([$data]);

        $this->assertInstanceOf(
            "EssentialsBundle\\Collection\\Metric\\MetricCollection",
            $metrics
        );

        $this->assertGreaterThan(0, count($metrics));
    }

    public function validMetricsData()
    {
        $metrics = [
            '{
                "name": "EBS.VolumeReadOps",
                "tags":[
                    {"key":"VolumeId","value":"vol-00d88e00b36757455"}
                ],
                "points":[{
                    "value":"6",
                    "maximum":"6",
                    "minimum":"6",
                    "sampleCount":"1",
                    "sum":"6",
                    "datetime":"2017-02-14T21:34:32-02:00",
                    "unit":"Count"
                }]
            }',
            '{
                "name": "EBS.VolumeReadOps",
                "tags":[
                    {"key":"VolumeId","value":"vol-00d88e00b36757455"}
                ],
                "points":[{
                    "value":"6",
                    "maximum":"6",
                    "minimum":"6",
                    "unit":"Count"
                }]
             }',
            '{
                "name": "EBS",
                "tags":[],
                "points":[{
                    "value":"6"
                }]
            }',
            '{
                "name": "EBS",
                "tags":[],
                "points":[]
             }'
        ];

        foreach ($metrics as $metric) {
            yield [ json_decode($metric, true) ];
        }
    }
}
