<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 11/08/17
 * Time: 15:36
 */

namespace DatabaseBundle\Tests\DataFixtures\NoSql\Configuration;

use DatabaseBundle\DataFixtures\NoSql\Configuration\Point;
use DatabaseBundle\DataFixtures\NoSql\Configuration\Series;
use DatabaseBundle\DataFixtures\NoSql\Configuration\TagCollection;
use DatabaseBundle\DataFixtures\NoSql\Configuration\Value;
use PHPUnit\Framework\TestCase;

class SeriesTest extends TestCase
{
    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $s = new Series(
            'test.unit',
            1,
            5,
            new TagCollection(),
            new Point(new Value('fixed', 1))
        );

        $this->assertEquals('test.unit', $s->getName());
        $this->assertEquals(1, $s->getTotal());
        $this->assertEquals(5, $s->getInterval());
        $this->assertInstanceOf(TagCollection::class, $s->getTags());
        $this->assertInstanceOf(Point::class, $s->getPoint());
    }
}
