<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 26/09/17
 * Time: 08:34
 */

namespace CollectorBundle\Tests\Collect;

use CollectorBundle\Collect\DataSource;
use PHPUnit\Framework\TestCase;

class DataSourceTest extends TestCase
{
    /**
     * @var DataSource
     */
    protected $ds;

    public function setUp()
    {
        $this->ds = new DataSource('test', 1, 300);
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals('test', $this->ds->getName());
        $this->assertEquals(1, $this->ds->getMaxConcurrency());
    }
}
