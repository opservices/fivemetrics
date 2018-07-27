<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 16/02/17
 * Time: 16:48
 */

namespace DataSourceBundle\Tests\Entity\Aws\Common\Tag;

use DataSourceBundle\Entity\Aws\Tag\Builder;
use PHPUnit\Framework\TestCase;

/**
 * Class BuilderTest
 * @package DataSourceBundle\Tests\Entity\Aws\Common\Tag
 */
class BuilderTest extends TestCase
{
    /**
     * @test
     * @dataProvider validTagsData
     * @param $data
     */
    public function buildTags($data)
    {
        $tags = Builder::build($data);

        $this->assertInstanceOf(
            "DataSourceBundle\\Collection\\Aws\\Tag\\TagCollection",
            $tags
        );

        $this->assertEquals(json_encode($data), json_encode($tags));
    }

    public function validTagsData()
    {
        $tags = [
            '[{
                "Key": "key",
                "Value": "value"
            }]',
            '[{
                "Key": "key",
                "Value": ""
            }]',
            '[]'
        ];

        foreach ($tags as $tag) {
            yield [ json_decode($tag, true) ];
        }
    }
}
