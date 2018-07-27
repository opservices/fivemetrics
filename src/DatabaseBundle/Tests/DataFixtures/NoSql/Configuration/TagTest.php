<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 11/08/17
 * Time: 15:24
 */

namespace DatabaseBundle\Tests\DataFixtures\NoSql\Configuration;

use DatabaseBundle\DataFixtures\NoSql\Configuration\Tag;
use DatabaseBundle\DataFixtures\NoSql\Configuration\Value;
use PHPUnit\Framework\TestCase;

class TagTest extends TestCase
{
    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $tag = new Tag('test', new Value('fixed', 'unit'));

        $this->assertEquals('test', $tag->getKey());
        $this->assertEquals('unit', $tag->getValue()->getData());
    }
}
