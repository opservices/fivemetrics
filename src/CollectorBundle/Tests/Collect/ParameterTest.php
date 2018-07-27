<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 26/09/17
 * Time: 08:38
 */

namespace CollectorBundle\Tests\Collect;

use CollectorBundle\Collect\Parameter;
use PHPUnit\Framework\TestCase;

class ParameterTest extends TestCase
{
    /**
     * @var Parameter
     */
    protected $param;

    public function setUp()
    {
        $this->param = new Parameter('test', 'unit');
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals('test', $this->param->getName());
        $this->assertEquals('unit', $this->param->getValue());
    }
}
