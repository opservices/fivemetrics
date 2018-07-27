<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 20/03/17
 * Time: 14:46
 */

namespace DataSourceBundle\Tests\Collection\Aws\Common\Tag;

use DataSourceBundle\Collection\Aws\Tag\TagCollection;
use DataSourceBundle\Entity\Aws\Tag\Tag;
use PHPUnit\Framework\TestCase;

/**
 * Class TagCollectionTest
 * @package DataSourceBundle\Test\Collection\Common\Tag
 */
class TagCollectionTest extends TestCase
{
    /**
     * @var TagCollection
     */
    protected $tags;

    public function setUp()
    {
        $this->tags = new TagCollection([ new Tag('test', 'unit') ]);
    }

    /**
     * @test
     */
    public function findTagSuccess()
    {
        $this->assertEquals(
            new Tag('test', 'unit'),
            $this->tags->find('test')
        );
    }

    /**
     * @test
     */
    public function findRemovedTag()
    {
        $this->tags->remove(0);
        $this->assertNull($this->tags->find('test'));
    }

    /**
     * @test
     */
    public function findTagFail()
    {
        $this->assertNull($this->tags->find('notFound'));
    }
}
