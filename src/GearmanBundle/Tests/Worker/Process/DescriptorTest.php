<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 02/03/17
 * Time: 15:06
 */

namespace GearmanBundle\Tests\Worker\Process;

use GearmanBundle\Worker\Process\Descriptor;
use PHPUnit\Framework\TestCase;

/**
 * Class DescriptorTest
 * @package GearmanBundle\Tests\Worker\Process
 */
class DescriptorTest extends TestCase
{
    /**
     * @var Descriptor
     */
    protected $desc;

    public function setUp()
    {
        $this->desc = new Descriptor('a', 'file', '/tmp/test');
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals(
            'a',
            $this->desc->getMode()
        );

        $this->assertEquals(
            'file',
            $this->desc->getType()
        );

        $this->assertEquals(
            '/tmp/test',
            $this->desc->getFile()
        );
    }

    /**
     * @test
     */
    public function toArray()
    {
        $this->assertEquals(
            [ 'file', '/tmp/test', 'a' ],
            $this->desc->toArray()
        );
    }

    /**
     * @test
     */
    public function setMode()
    {
        $this->desc->setMode('w');

        $this->assertEquals(
            'w',
            $this->desc->getMode()
        );
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function setInvalidMode()
    {
        $this->desc->setMode('f');
    }

    /**
     * @test
     */
    public function setType()
    {
        $this->desc->setType('pipe');

        $this->assertEquals(
            'pipe',
            $this->desc->getType()
        );
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function setInvalidType()
    {
        $this->desc->setType('test');
    }

    /**
     * @test
     */
    public function setFile()
    {
        $this->desc->setFile('test');

        $this->assertEquals(
            'test',
            $this->desc->getFile()
        );
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function setInvalidFile()
    {
        $this->desc->setFile('');
    }
}
