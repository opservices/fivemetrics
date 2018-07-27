<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 11/08/17
 * Time: 15:46
 */

namespace DatabaseBundle\Tests\DataFixtures\NoSql\Configuration;

use DatabaseBundle\DataFixtures\NoSql\Configuration\Point;
use DatabaseBundle\DataFixtures\NoSql\Configuration\Series;
use DatabaseBundle\DataFixtures\NoSql\Configuration\SeriesCollection;
use DatabaseBundle\DataFixtures\NoSql\Configuration\TagCollection;
use DatabaseBundle\DataFixtures\NoSql\Configuration\Value;
use PHPUnit\Framework\TestCase;

class SeriesCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $series = new Series(
            'test',
            1,
            5,
            new TagCollection(),
            new Point(new Value('fixed', 1))
        );

        $seriesCollection = new SeriesCollection([ $series ]);

        $this->assertSame($series, $seriesCollection->at(0));
    }
}
