<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 17/02/17
 * Time: 14:42
 */

namespace DataSourceBundle\Tests\Entity\Aws\EC2\Instance;

use DataSourceBundle\Entity\Aws\EC2\Instance\InstanceState;
use PHPUnit\Framework\TestCase;

/**
 * Class InstanceStateTest
 * @package DataSourceBundle\Tests\Entity\Aws\EC2\Instance
 */
class InstanceStateTest extends TestCase
{
    /**
     * @var InstanceState
     */
    protected $state;

    public function setUp()
    {
        $this->state = new InstanceState(
            1,
            'pending'
        );
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals(
            1,
            $this->state->getCode()
        );

        $this->assertEquals(
            'pending',
            $this->state->getName()
        );
    }

    /**
     * @test
     */
    public function setCode()
    {
        $this->state->setCode(2);

        $this->assertEquals(
            2,
            $this->state->getCode()
        );
    }

    /**
     * @test
     */
    public function setStateName()
    {
        $this->state->setName('running');

        $this->assertEquals(
            'running',
            $this->state->getName()
        );
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function setInvalidStateName()
    {
        $this->state->setName('test');
    }
}
