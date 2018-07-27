<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 11/08/17
 * Time: 15:22
 */

namespace DatabaseBundle\Tests\DataFixtures\NoSql\Configuration;

use DatabaseBundle\DataFixtures\NoSql\Configuration\Range;
use PHPUnit\Framework\TestCase;

class RangeTest extends TestCase
{
    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $range = new Range(1, 5);

        $this->assertEquals(1, $range->getMinimum());
        $this->assertEquals(5, $range->getMaximum());
    }
}
