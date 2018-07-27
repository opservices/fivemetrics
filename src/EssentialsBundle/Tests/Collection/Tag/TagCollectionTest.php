<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 20/03/17
 * Time: 14:46
 */

namespace EssentialsBundle\Tests\Collection\Tag;

use EssentialsBundle\Collection\Tag\TagCollection;
use EssentialsBundle\Entity\Tag\Tag;
use PHPUnit\Framework\TestCase;

/**
 * Class TagCollectionTest
 * @package DataSourceBundle\Test\Collection\Tag
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
    public function findTagFail()
    {
        $this->assertNull($this->tags->find('notFound'));
    }

    /**
     * @test
     */
    public function findAfterRemoveAnElement()
    {
        $expected = new Tag('test3', 'unit3');
        $this->tags->add(new Tag('test2', 'unit2'))
            ->add(new Tag('test3', 'unit3'));

        unset($this->tags[1]);

        $this->assertEquals(
            $expected,
            $this->tags->find('test3')
        );

        $this->assertNull($this->tags->find('test2'));
    }

    /**
     * @test
     */
    public function toString()
    {
        $this->assertEquals(
            'test:unit',
            (string)$this->tags
        );
    }

    /**
     * @test
     */
    public function toArray()
    {
        $this->assertEquals(
            [ [ 'key' => 'test', 'value' => 'unit' ] ],
            $this->tags->toArray()
        );
    }

    /**
     * @test
     */
    public function toInfluxTagArray()
    {
        $this->assertEquals(
            [ 'test' => 'unit' ],
            $this->tags->toInfluxTagArray()
        );
    }
}
