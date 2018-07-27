<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 27/03/17
 * Time: 21:08
 */

namespace DataSourceBundle\Tests\Collection\Aws\CloudWatch;

use DataSourceBundle\Collection\Aws\CloudWatch\DimensionCollection;
use DataSourceBundle\Entity\Aws\CloudWatch\Dimension;
use PHPUnit\Framework\TestCase;

class DimensionCollectionTest extends TestCase
{
    /**
     * @var DimensionCollection
     */
    protected $dimensions;

    public function setUp()
    {
        $this->dimensions = new DimensionCollection([
            new Dimension('test', 'unit')
        ]);
    }

    /**
     * @test
     */
    public function toArray()
    {
        $this->assertEquals(
            [[ 'Name' => 'test', 'Value' => 'unit' ]],
            $this->dimensions->toArray()
        );
    }
}
