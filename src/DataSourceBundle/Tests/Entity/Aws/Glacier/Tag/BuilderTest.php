<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 8/4/17
 * Time: 4:03 PM
 */

namespace DataSourceBundle\Tests\Entity\Aws\Glacier\Tag;

use DataSourceBundle\Collection\Aws\Tag\TagCollection;
use DataSourceBundle\Entity\Aws\Glacier\Tag\Builder as TagBuilder;
use PHPUnit\Framework\TestCase;

/**
 * Class BuilderTest
 * @package DataSourceBundle\Tests\Entity\Aws\Glacier\Tag
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
        $tags = TagBuilder::build($data);

        $this->assertInstanceOf(
            TagCollection::class,
            $tags
        );

        if (!empty($data)) {
            $object = new \StdClass();
            $object->Key = 'foo';
            $object->Value = 'bar';
            $data = [$object];
        }

        $this->assertEquals(json_encode($data), json_encode($tags));
    }

    public function validTagsData()
    {
        $tags = [
            '[{
                "foo" : "bar"
            }]',
            '[
                []
            ]'
        ];

        foreach ($tags as $tag) {
            yield json_decode($tag, true);
        }
    }
}
