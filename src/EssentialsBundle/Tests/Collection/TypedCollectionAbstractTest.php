<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 20/02/17
 * Time: 08:41
 */

namespace EssentialsBundle\Tests\Collection;

use EssentialsBundle\Collection\TypedCollectionAbstract;
use EssentialsBundle\Entity\Tag\Tag;
use PHPUnit\Framework\TestCase;

/**
 * Class Collection
 * @package Test\Collection\Common
 */
class Collection extends TypedCollectionAbstract
{
    /**
     * @return string
     */
    public function getClass(): string
    {
        return Tag::class;
    }

    protected function onChanged($added = null, $removed = null)
    {
    }
}

/**
 * Class AbstractCollectionTest
 * @package Test\Collection\Common
 */
class TypedCollectionAbstractTest extends TestCase
{
    /**
     * @var Collection
     */
    protected $collection;

    public function setUp()
    {
        $this->collection = new Collection();
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function tryAddInvalidElement()
    {
        $this->collection->add(new Collection());
    }

    /**
     * @test
     */
    public function concatTwoCollections()
    {
        $tmpCollection = new Collection();

        $this->collection->add(new Tag('key', 'value'));
        $tmpCollection->add(new Tag('key1', 'value1'));
        $tmpCollection->add(new Tag('key2', 'value2'));

        $this->collection->concat($tmpCollection);

        $this->assertEquals(3, count($this->collection));
    }

    /**
     * @test
     */
    public function getAndSetElementsAsArray()
    {
        $this->collection[] = new Tag('test1', 'unit');
        $this->collection[0] = new Tag('test2', 'unit2');

        $this->assertCount(1, $this->collection);
        $this->assertEquals(
            new Tag('test2', 'unit2'),
            $this->collection[0]
        );
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function setInvalidElementAsArray()
    {
        $this->collection[] = new Tag('test1', 'unit');
        $this->collection[0] = 1;
    }

    /**
     * @test
     */
    public function usort()
    {
        $tmpCollection = new Collection();
        $tmpCollection->add(new Tag('key1', 1));
        $tmpCollection->add(new Tag('key3', 2));
        $tmpCollection->add(new Tag('key2', 3));

        $this->collection->add(new Tag('key1', 1));
        $this->collection->add(new Tag('key2', 3));
        $this->collection->add(new Tag('key3', 2));

        $this->collection->usort(function (Tag $a, Tag $b) {
            return ($a->getValue() > $b->getValue());
        });

        $this->assertEquals(
            json_encode($tmpCollection),
            json_encode($this->collection)
        );
    }

    /**
     * @test
     * @expectedException \OutOfBoundsException
     */
    public function atInvalidIndex()
    {
        $this->collection->at(1);
    }

    /**
     * @test
     */
    public function toArray()
    {
        $this->collection->add(new Tag('key', 'value'));
        $this->assertEquals(
            [ [ 'key' => 'key', 'value' => 'value' ] ],
            $this->collection->toArray()
        );
    }

    /**
     * @test
     */
    public function isEmptyTest()
    {
        $this->assertTrue($this->collection->isEmpty());
        $this->collection->add(new Tag('key', 'value'));
        $this->assertFalse($this->collection->isEmpty());
    }

    /**
     * @test
     */
    public function heap()
    {
        $tag = new Tag('key', 'value');

        $this->assertEmpty($this->collection);

        $this->collection->push($tag);

        $this->assertCount(1, $this->collection);
        $this->assertSame($tag, $this->collection->at(0));
        $this->assertEquals($tag, $this->collection->pop());
        $this->assertEmpty($this->collection);
    }
}
