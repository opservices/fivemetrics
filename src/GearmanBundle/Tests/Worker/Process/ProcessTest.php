<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 02/03/17
 * Time: 15:54
 */

namespace GearmanBundle\Tests\Worker\Process;

use GearmanBundle\Collection\Worker\Process\DescriptorCollection;
use EssentialsBundle\Entity\Shell\Command\Command;
use GearmanBundle\Worker\Process\Process;
use GearmanBundle\Worker\Process\Status;
use PHPUnit\Framework\TestCase;
use EssentialsBundle\Reflection;

/**
 * Class ProcessTest
 * @package GearmanBundle\Tests\Worker\Process
 */
class ProcessTest extends TestCase
{
    /**
     * @var Process
     */
    protected $proc;

    public function setUp()
    {
        $this->proc = new Process(
            new DescriptorCollection(),
            new Command('/bin/ls')
        );
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals(
            '/bin/ls',
            $this->proc->getCommand()->getExecutable()
        );

        $this->assertInstanceOf(
            'GearmanBundle\Collection\Worker\Process\DescriptorCollection',
            $this->proc->getDescriptors()
        );
    }

    /**
     * @test
     */
    public function start()
    {
        $fnCaller = $this->getMockBuilder('EssentialsBundle\FunctionCaller');

        $fnCaller = $fnCaller->setMethods([ 'proc_open' ])
            ->getMock();

        $fnCaller->expects($this->once())
            ->method('proc_open')
            ->will($this->returnValue('test'));

        Reflection::setPropertyOnObject($this->proc, 'fnCaller', $fnCaller);

        $this->assertEquals(
            'test',
            $this->proc->start()->getHandler()
        );
    }

    /**
     * @test
     */
    public function setPipes()
    {
        $this->proc->setPipes([ 1 ]);

        $this->assertEquals(
            [ 1 ],
            $this->proc->getPipes()
        );
    }

    /**
     * @test
     */
    public function getStatus()
    {
        $fnCaller = $this->getMockBuilder('EssentialsBundle\FunctionCaller');

        $fnCaller = $fnCaller->setMethods([ 'proc_get_status' ])
            ->getMock();

        $fnCaller->expects($this->once())
            ->method('proc_get_status')
            ->will($this->returnValue(false));

        Reflection::setPropertyOnObject($this->proc, 'fnCaller', $fnCaller);

        $this->assertInstanceOf(
            'GearmanBundle\Worker\Process\Status',
            $this->proc->getStatus()
        );
    }

    /**
     * @test
     */
    public function stop()
    {
        $fnCaller = $this->getMockBuilder('EssentialsBundle\FunctionCaller');

        $fnCaller = $fnCaller->setMethods([ 'proc_terminate' ])
            ->getMock();

        $fnCaller->expects($this->once())
            ->method('proc_terminate')
            ->will($this->returnValue(true));

        Reflection::setPropertyOnObject($this->proc, 'fnCaller', $fnCaller);

        $this->assertTrue($this->proc->stop());
    }

    /**
     * @test
     */
    public function stopForced()
    {
        $fnCaller = $this->getMockBuilder('EssentialsBundle\FunctionCaller');

        $fnCaller = $fnCaller->setMethods([ 'proc_terminate',  'posix_kill' ])
            ->getMock();

        $fnCaller->expects($this->once())
            ->method('proc_terminate')
            ->will($this->returnValue(false));

        $fnCaller->expects($this->once())
            ->method('posix_kill')
            ->will($this->returnValue(true));

        $proc = $this->getMockBuilder('GearmanBundle\Worker\Process\Process');
        $proc = $proc->setMethods([ 'getStatus' ])
            ->disableOriginalConstructor()
            ->getMock();

        $proc->expects($this->once())
            ->method('getStatus')
            ->will($this->returnValue(new Status(
                'command',
                12345,
                true,
                false,
                false,
                false,
                0,
                0
            )));

        Reflection::setPropertyOnObject($proc, 'fnCaller', $fnCaller);

        $this->assertTrue($proc->stop());
    }
}
