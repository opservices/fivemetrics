<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 02/03/17
 * Time: 19:27
 */

namespace GearmanBundle\Tests\TaskManager;

use GearmanBundle\Collection\Configuration\JobServerCollection;
use GearmanBundle\Collection\Configuration\WorkerCollection;
use GearmanBundle\Entity\Configuration;
use GearmanBundle\TaskManager\TaskManager;
use PHPUnit\Framework\TestCase;
use EssentialsBundle\Reflection;

/**
 * Class TaskManagerTest
 * @package GearmanBundle\Tests\TaskManager
 */
class TaskManagerTest extends TestCase
{
    /**
     * @var TaskManager
     */
    protected $taskManager;

    public function setUp()
    {
        $gclient = new class extends \GearmanClient {
            public function addServers($servers = '127.0.0.1:4730')
            {
                return true;
            }
        };

        $file = $this->getMockBuilder('GearmanBundle\Configuration\DataSource\File');
        $file = $file->setMethods([ 'load' ])
            ->getMock();

        $workers = new WorkerCollection();
        $workers->add(new Configuration\Worker('Test', 1));

        $jobServers = new JobServerCollection();
        $jobServers->add(new Configuration\JobServer('127.0.0.1'));

        $file->expects($this->any())
            ->method('load')
            ->will($this->returnValue(
                new Configuration(
                    $workers,
                    $jobServers
                )
            ));

        $this->taskManager = new TaskManager($file, $gclient);
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function openSocketFail()
    {
        $fnCaller = $this->getMockBuilder('EssentialsBundle\FunctionCaller');

        $fnCaller = $fnCaller->setMethods([ 'fsockopen' ])
            ->getMock();

        $fnCaller->expects($this->once())
            ->method('fsockopen')
            ->will($this->returnValue(false));

        Reflection::setPropertyOnObject(
            $this->taskManager,
            'fnCaller',
            $fnCaller
        );

        Reflection::callMethodOnObject(
            $this->taskManager,
            'openSocket',
            [ '127.0.0.1', 4730 ]
        );
    }

    /**
     * @test
     */
    public function openSocket()
    {
        $fnCaller = $this->getMockBuilder('EssentialsBundle\FunctionCaller');

        $fnCaller = $fnCaller->setMethods([ 'fsockopen', 'stream_set_timeout' ])
            ->getMock();

        $fnCaller->expects($this->once())
            ->method('fsockopen')
            ->will($this->returnValue('test'));

        $fnCaller->expects($this->once())
            ->method('stream_set_timeout')
            ->will($this->returnValue(true));

        Reflection::setPropertyOnObject(
            $this->taskManager,
            'fnCaller',
            $fnCaller
        );

        $this->assertEquals(
            'test',
            Reflection::callMethodOnObject(
                $this->taskManager,
                'openSocket',
                [ false, 4730 ]
            )
        );
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function sendCommandFail()
    {
        $fnCaller = $this->getMockBuilder('EssentialsBundle\FunctionCaller');

        $fnCaller = $fnCaller->setMethods([ 'is_resource' ])
            ->getMock();

        $fnCaller->expects($this->once())
            ->method('is_resource')
            ->will($this->returnValue(false));

        Reflection::setPropertyOnObject(
            $this->taskManager,
            'fnCaller',
            $fnCaller
        );

        Reflection::callMethodOnObject(
            $this->taskManager,
            'sendCommand',
            [ false, false ]
        );
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function runBackgroundInvalidPriority()
    {
        $this->taskManager->runBackground('test', 'data', 100000);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function runInvalidPriority()
    {
        $this->taskManager->run('test', 'data', 100000);
    }

    /**
     * @test
     * @dataProvider taskManagerPrioritiesProvider
     */
    public function runBackground($priority)
    {
        $gClient = $this->getMockBuilder('\GearmanClient');

        $gClient = $gClient->setMethods([
            'doLowBackground',
            'doBackground',
            'doHighBackground'
        ])->getMock();

        $gClient->expects($this->any())
            ->method('doLowBackground')
            ->will($this->returnValue('unitTest'));

        $gClient->expects($this->any())
            ->method('doBackground')
            ->will($this->returnValue('unitTest'));

        $gClient->expects($this->any())
            ->method('doHighBackground')
            ->will($this->returnValue('unitTest'));

        $this->taskManager->setClient($gClient);

        $this->assertEquals(
            'unitTest',
            $this->taskManager->runBackground(
                'test',
                'data',
                $priority
            )
        );
    }

    /**
     * @test
     * @dataProvider taskManagerPrioritiesProvider
     */
    public function runForeground($priority)
    {
        $gClient = $this->getMockBuilder('\GearmanClient');

        $gClient = $gClient->setMethods([
            'doLow',
            'doNormal',
            'doHigh'
        ])->getMock();

        $gClient->expects($this->any())
            ->method('doLow')
            ->will($this->returnValue('unitTest'));

        $gClient->expects($this->any())
            ->method('doNormal')
            ->will($this->returnValue('unitTest'));

        $gClient->expects($this->any())
            ->method('doHigh')
            ->will($this->returnValue('unitTest'));

        $this->taskManager->setClient($gClient);

        $this->assertEquals(
            'unitTest',
            $this->taskManager->run(
                'test',
                'data',
                $priority
            )
        );
    }

    public function taskManagerPrioritiesProvider()
    {
        return [
            [ TaskManager::NORMAL ],
            [ TaskManager::HIGH ],
            [ TaskManager::VERY_HIGH ]
        ];
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function runBackgroundFailedToSubmit()
    {
        $gClient = $this->getMockBuilder('\GearmanClient');

        $gClient = $gClient->setMethods([ 'doLowBackground', 'returnCode' ])
            ->getMock();

        $gClient->expects($this->once())
            ->method('doLowBackground')
            ->will($this->returnValue('unitTest'));

        $gClient->expects($this->once())
            ->method('returnCode')
            ->will($this->returnValue(GEARMAN_WORK_FAIL));

        $this->taskManager->setClient($gClient);

        $this->assertEquals(
            'unitTest',
            $this->taskManager->runBackground(
                'test',
                'data',
                TaskManager::NORMAL
            )
        );
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function runForegroundFailedToSubmit()
    {
        $gClient = $this->getMockBuilder('\GearmanClient');

        $gClient = $gClient->setMethods([ 'doLow', 'returnCode' ])
            ->getMock();

        $gClient->expects($this->once())
            ->method('doLow')
            ->will($this->returnValue('unitTest'));

        $gClient->expects($this->once())
            ->method('returnCode')
            ->will($this->returnValue(GEARMAN_WORK_FAIL));

        $this->taskManager->setClient($gClient);

        $this->assertEquals(
            'unitTest',
            $this->taskManager->run(
                'test',
                'data',
                TaskManager::NORMAL
            )
        );
    }

    /**
     * @test
     */
    public function getFnCaller()
    {
        $this->assertInstanceOf(
            'EssentialsBundle\FunctionCaller',
            Reflection::callMethodOnObject($this->taskManager, 'getFnCaller')
        );
    }

    /**
     * @test
     */
    public function getClient()
    {
        $this->assertInstanceOf(
            '\GearmanClient',
            $this->taskManager->getClient()
        );
    }
}
