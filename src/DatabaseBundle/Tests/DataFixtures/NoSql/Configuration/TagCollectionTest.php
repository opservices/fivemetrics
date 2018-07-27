<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 11/08/17
 * Time: 15:42
 */

namespace DatabaseBundle\Tests\DataFixtures\NoSql\Configuration;

use DatabaseBundle\DataFixtures\NoSql\Configuration\Tag;
use DatabaseBundle\DataFixtures\NoSql\Configuration\TagCollection;
use DatabaseBundle\DataFixtures\NoSql\Configuration\Value;
use PHPUnit\Framework\TestCase;

class TagCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $tag = new Tag('test', new Value('fixed', 'unit'));
        $tags = new TagCollection([ $tag ]);

        $this->assertSame($tag, $tags->at(0));
    }
}
