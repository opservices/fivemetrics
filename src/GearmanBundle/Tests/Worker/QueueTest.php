<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 02/03/17
 * Time: 14:48
 */

namespace GearmanBundle\Tests\Gearman\Worker;

use GearmanBundle\Worker\Queue;
use PHPUnit\Framework\TestCase;

/**
 * Class QueueTest
 * @package GearmanBundle\Tests\Gearman\Worker
 */
class QueueTest extends TestCase
{
    /**
     * @var Queue
     */
    protected $queue;

    public function setUp()
    {
        $this->queue = new Queue("testQueue", "methodQueue");
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals(
            "testQueue",
            $this->queue->getName()
        );

        $this->assertEquals(
            "methodQueue",
            $this->queue->getMethod()
        );
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function setInvalidName()
    {
        $this->queue->setName("");
    }

    /**
     * @test
     */
    public function setQueueName()
    {
        $this->queue->setName("unitTest");

        $this->assertEquals(
            "unitTest",
            $this->queue->getName()
        );
    }

    /**
     * @test
     */
    public function setMethod()
    {
        $this->queue->setMethod("unitTest");

        $this->assertEquals(
            "unitTest",
            $this->queue->getMethod()
        );
    }
}
