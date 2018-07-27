<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 02/03/17
 * Time: 16:32
 */

namespace GearmanBundle\Tests\Worker\Manager;

use GearmanBundle\Entity\Configuration\Worker;
use GearmanBundle\Worker\Manager\Queue;
use PHPUnit\Framework\TestCase;

/**
 * Class QueueTest
 * @package GearmanBundle\Tests\Worker\Manager
 */
class QueueTest extends TestCase
{
    /**
     * @var Queue
     */
    protected $queue;

    public function setUp()
    {
        $this->queue = new Queue(
            new Worker('Test', 1)
        );
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals(
            new Worker('Test', 1),
            $this->queue->getConfiguration()
        );

        $this->assertInstanceOf(
            'GearmanBundle\Collection\Worker\Process\ProcessCollection',
            $this->queue->getProcesses()
        );
    }
}
