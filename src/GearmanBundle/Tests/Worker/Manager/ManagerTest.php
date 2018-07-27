<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 02/03/17
 * Time: 16:25
 */

namespace GearmanBundle\Tests\Worker;

use GearmanBundle\Collection\Worker\Manager\QueueCollection;
use GearmanBundle\Collection\Worker\Process\ProcessCollection;
use GearmanBundle\Entity\Configuration\Worker;
use GearmanBundle\Worker\Manager\Manager;
use GearmanBundle\Worker\Manager\Queue;
use GearmanBundle\Worker\Process\Status;
use PHPUnit\Framework\TestCase;
use EssentialsBundle\Reflection;

/**
 * Class ManagerTest
 * @package GearmanBundle\Tests\Worker
 */
class ManagerTest extends TestCase
{
    /**
     * @var Manager
     */
    protected $manager;

    public function setUp()
    {
        $queues = new QueueCollection();
        $queues->add(new Queue(new Worker('Test', 1)));
        $this->manager = new Manager($queues);
    }

    /**
     * @test
     */
    public function addQueue()
    {
        $this->manager->addQueue(
            new Queue(new Worker('UnitTest', 1))
        );

        $this->assertEquals(
            2,
            count($this->manager->getQueues())
        );
    }

    /**
     * @test
     */
    public function getProcessInstance()
    {
        $process = Reflection::callMethodOnObject(
            $this->manager,
            'getProcessInstance',
            [ new Worker('Test', 1) ]
        );

        $this->assertInstanceOf(
            'GearmanBundle\Worker\Process\Process',
            $process
        );
    }

    /**
     * @test
     */
    public function hasWorkerRunningFalse()
    {
        $this->assertFalse(
            $this->manager->hasWorkerRunning(
                $this->manager->getQueues()->current()
            )
        );
    }

    /**
     * @test
     */
    public function hasWorkerRunningFalseWithAllProcessStopped()
    {
        $proc = $this->getMockBuilder('GearmanBundle\Worker\Process\Process');
        $proc = $proc->setMethods([ 'getStatus' ])
            ->disableOriginalConstructor()
            ->getMock();

        $proc->expects($this->once())
            ->method('getStatus')
            ->will($this->returnValue(
                new Status(
                    'command',
                    12345,
                    false,
                    false,
                    true,
                    false,
                    0,
                    0
                )
            ));

        $processes = new ProcessCollection();
        $processes->add($proc);

        $queues = new QueueCollection();
        $queues->add(new Queue(new Worker('Test', 1), $processes));

        $manager = new Manager($queues);

        $this->assertFalse(
            $manager->hasWorkerRunning(
                $manager->getQueues()->at(0)
            )
        );
    }

    /**
     * @test
     */
    public function hasWorkerRunningTrue()
    {
        $proc = $this->getMockBuilder('GearmanBundle\Worker\Process\Process');
        $proc = $proc->setMethods([ 'getStatus' ])
            ->disableOriginalConstructor()
            ->getMock();

        $proc->expects($this->once())
            ->method('getStatus')
            ->will($this->returnValue(
                new Status(
                    'command',
                    12345,
                    true,
                    false,
                    false,
                    false,
                    0,
                    0
                )
            ));

        $processes = new ProcessCollection();
        $processes->add($proc);

        $queues = new QueueCollection();
        $queues->add(new Queue(new Worker('Test', 1), $processes));

        $manager = new Manager($queues);

        $this->assertTrue(
            $manager->hasWorkerRunning(
                $manager->getQueues()->current()
            )
        );
    }

    /**
     * @test
     */
    public function stopWorkers()
    {
        $proc = $this->getMockBuilder('GearmanBundle\Worker\Process\Process');
        $proc = $proc->setMethods([ 'stop' ])
            ->disableOriginalConstructor()
            ->getMock();

        $proc->expects($this->once())
            ->method('stop')
            ->will($this->returnValue(true));

        $processes = new ProcessCollection();
        $processes->add($proc);

        $this->manager->getQueues()->at(0)->setProcesses($processes);

        $this->assertEquals(
            1,
            count($this->manager->getQueues()->at(0)->getProcesses())
        );

        $this->manager->stopWorkers();

        $this->assertEquals(
            0,
            count($this->manager->getQueues()->at(0)->getProcesses())
        );
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function startWorkersWithFail()
    {
        $queues = new QueueCollection();
        $queues->add(new Queue(new Worker('Test', 1)));

        $manager = $this->getMockBuilder('GearmanBundle\Worker\Manager\Manager');
        $manager = $manager->setMethods([ 'hasWorkerRunning' ])
            ->getMock();

        $manager->expects($this->once())
            ->method('hasWorkerRunning')
            ->will($this->returnValue(true));

        $manager->setQueues($queues);
        $manager->startWorkers();
    }

    /**
     * @test
     */
    public function startWorkers()
    {
        $proc = $this->getMockBuilder('GearmanBundle\Worker\Process\Process');
        $proc = $proc->setMethods([ 'start' ])
            ->disableOriginalConstructor()
            ->getMock();

        $proc->expects($this->exactly(2))
            ->method('start')
            ->will($this->returnValue($proc));

        $queues = new QueueCollection();
        $queues->add(new Queue(new Worker('Test', 2)));

        $manager = $this->getMockBuilder('GearmanBundle\Worker\Manager\Manager');
        $manager = $manager->setMethods([ 'getProcessInstance' ])
            ->getMock();

        $manager->expects($this->exactly(2))
            ->method('getProcessInstance')
            ->will($this->returnValue($proc));

        $manager->setQueues($queues);
        $manager->startWorkers();

        $this->assertEquals(
            2,
            count($manager->getQueues()->at(0)->getProcesses())
        );
    }
}
