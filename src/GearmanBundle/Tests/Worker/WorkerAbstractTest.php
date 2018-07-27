<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 02/03/17
 * Time: 18:29
 */

namespace GearmanBundle\Tests\Worker;

use GearmanBundle\Collection\Configuration\JobServerCollection;
use GearmanBundle\Collection\Worker\QueueCollection;
use GearmanBundle\Entity\Configuration\JobServer;
use GearmanBundle\Worker\WorkerAbstract;
use GearmanBundle\Worker\Queue;
use PHPUnit\Framework\TestCase;
use EssentialsBundle\Reflection;

class Worker extends WorkerAbstract
{
    protected function getQueues(): QueueCollection
    {
        $queues = new QueueCollection();
        $queues->add(new Queue('test-queue', 'method'));
        return $queues;
    }
}

/**
 * Class WorkerAbstractTest
 * @package GearmanBundle\Test\Worker
 */
class WorkerAbstractTest extends TestCase
{
    /**
     * @var Worker
     */
    protected $worker;

    public function setUp()
    {
        $this->worker = new Worker();
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function setIoTimeoutInvalid()
    {
        $this->worker->setIoTimeout(-1);
    }

    /**
     * @test
     */
    public function setIoTimeout()
    {
        $this->worker->setIoTimeout(2000);

        $this->assertEquals(
            2000,
            $this->worker->getIoTimeout()
        );
    }

    /**
     * @test
     */
    public function getWorker()
    {
        $this->assertInstanceOf(
            '\GearmanWorker',
            Reflection::callMethodOnObject($this->worker, 'getWorker')
        );
    }

    /**
     * @test
     */
    public function setJobServers()
    {
        $jobServers = new JobServerCollection();
        $jobServers->add(new JobServer('127.0.0.1'));

        $this->worker->setJobServers($jobServers);

        $this->assertEquals(
            $jobServers,
            $this->worker->getJobServers()
        );
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function setEmptyJobServers()
    {
        $jobServers = new JobServerCollection();
        $this->worker->setJobServers($jobServers);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function registerMethodsEmpty()
    {
        $worker = $this->getMockBuilder('GearmanBundle\Tests\Worker\Worker');
        $worker = $worker->setMethods([ 'getQueues' ])
            ->getMock();

        $worker->expects($this->once())
            ->method('getQueues')
            ->will($this->returnValue(new QueueCollection()));

        Reflection::callMethodOnObject($worker, 'registerMethods');
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function prepareWorkerWithEmptyJobServers()
    {
        Reflection::setPropertyOnObject(
            $this->worker,
            'jobServers',
            new JobServerCollection()
        );
        Reflection::callMethodOnObject($this->worker, 'prepareWorker');
    }

    /**
     * @test
     */
    public function prepareWorker()
    {
        $gearmanWorker = $this->getMockBuilder('\GearmanWorker');
        $gearmanWorker = $gearmanWorker->setMethods([ 'addServers' ])
            ->getMock();

        $gearmanWorker->expects($this->once())
            ->method('addServers')
            ->will($this->returnValue(true));

        $worker = new Worker(new JobServerCollection([
            new JobServer('127.0.0.1')
        ]), $gearmanWorker);

        $this->assertInstanceOf(
            'GearmanBundle\Worker\WorkerAbstract',
            Reflection::callMethodOnObject($worker, 'prepareWorker')
        );
    }
}
