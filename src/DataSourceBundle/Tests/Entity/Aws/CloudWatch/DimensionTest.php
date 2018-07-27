<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 16/02/17
 * Time: 17:02
 */

namespace DataSourceBundle\Tests\Entity\Aws\CloudWatch;

use DataSourceBundle\Entity\Aws\CloudWatch\Dimension;
use PHPUnit\Framework\TestCase;

/**
 * Class DimensionTest
 * @package DataSourceBundle\Tests\Entity\Aws\CloudWatch
 */
class DimensionTest extends TestCase
{
    /**
     * @var Dimension
     */
    protected $dim;

    public function setUp()
    {
        $this->dim = new Dimension("name", "value");
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals(
            "name",
            $this->dim->getName()
        );

        $this->assertEquals(
            "value",
            $this->dim->getValue()
        );
    }

    /**
     * @test
     */
    public function setDimName()
    {
        $this->dim->setName("name.test");

        $this->assertEquals(
            "name.test",
            $this->dim->getName()
        );
    }

    /**
     * @test
     */
    public function setDimValue()
    {
        $this->dim->setValue("value.test");

        $this->assertEquals(
            "value.test",
            $this->dim->getValue()
        );
    }
}
