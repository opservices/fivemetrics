<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 17/02/17
 * Time: 14:58
 */

namespace EssentialsBundle\Tests\Shell\Command;

use EssentialsBundle\Entity\Shell\Command\Command;
use PHPUnit\Framework\TestCase;
use EssentialsBundle\Reflection;
use EssentialsBundle\Shell\Command\Runner;

/**
 * Class RunnerTest
 * @package Test\EssentialsBundle\Shell\Command
 */
class RunnerTest extends TestCase
{
    /**
     * @var Runner
     */
    protected $runner;

    public function setUp()
    {
        $this->runner = new Runner();
    }

    /**
     * @test
     */
    public function setExitCode()
    {
        Reflection::callMethodOnObject($this->runner, 'setExitCode', [1]);

        $this->assertEquals(
            1,
            $this->runner->getExitCode()
        );
    }

    /**
     * @test
     */
    public function setStdout()
    {
        Reflection::callMethodOnObject($this->runner, 'setStdout', ['stdout']);

        $this->assertEquals(
            'stdout',
            $this->runner->getStdout()
        );
    }

    /**
     * @test
     */
    public function setStderr()
    {
        Reflection::callMethodOnObject($this->runner, 'setStderr', ['stderr']);

        $this->assertEquals(
            'stderr',
            $this->runner->getStderr()
        );
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function runEmptyCommand()
    {
        $this->runner->run(new Command(""));
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function runCommandTimeout()
    {
        $runner = $this->getMockBuilder('EssentialsBundle\Shell\Command\Runner');
        $runner = $runner->setMethods(
            [
                'runCommand',
                'setStreamBlocking',
                'setExitCode',
                'readOutput',
                'isCommandOver',
                'endProcess'
            ]
        )->getMock();

        $runner->expects($this->once())
            ->method('runCommand')
            ->will($this->returnValue(true));

        $runner->expects($this->once())
            ->method('setStreamBlocking')
            ->will($this->returnValue($this->runner));

        $runner->expects($this->once())
            ->method('setExitCode')
            ->will($this->returnValue($this->runner));

        $runner->expects($this->any())
            ->method('readOutput')
            ->will($this->returnValue(''));

        $runner->expects($this->any())
            ->method('isCommandOver')
            ->will($this->returnValue(false));

        $runner->expects($this->once())
            ->method('endProcess')
            ->will($this->returnValue(true));

        $runner->run(new Command("ls"), 0);
    }

    /**
     * @test
     */
    public function runCommandSuccess()
    {
        $runner = $this->getMockBuilder('EssentialsBundle\Shell\Command\Runner');
        $runner = $runner->setMethods(
            [
                'runCommand',
                'setStreamBlocking',
                'setExitCode',
                'readOutput',
                'isCommandOver'
            ]
        )->getMock();

        $runner->expects($this->once())
            ->method('runCommand')
            ->will($this->returnValue(true));

        $runner->expects($this->once())
            ->method('setStreamBlocking')
            ->will($this->returnValue($this->runner));

        $runner->expects($this->any())
            ->method('setExitCode')
            ->will($this->returnValue($this->runner));

        $runner->expects($this->any())
            ->method('readOutput')
            ->will($this->returnValue(''));

        $runner->expects($this->any())
            ->method('isCommandOver')
            ->will($this->returnValue(true));

        $this->assertInstanceOf(
            'EssentialsBundle\Shell\Command\Runner',
            $runner->run(new Command("ls"), 0)
        );
    }

    /**
     * @test
     */
    public function runCommandWithoutTimeout()
    {
        $runner = $this->getMockBuilder('EssentialsBundle\Shell\Command\Runner');
        $runner = $runner->setMethods(
            [
                'runCommand',
                'setStdout',
                'setStderr',
                'setExitCode',
                'readOutput'
            ]
        )->getMock();

        $runner->expects($this->once())
            ->method('runCommand')
            ->will($this->returnValue(true));

        $runner->expects($this->any())
            ->method('setStdout')
            ->will($this->returnValue($this->runner));

        $runner->expects($this->any())
            ->method('setExitCode')
            ->will($this->returnValue($this->runner));

        $runner->expects($this->any())
            ->method('setStderr')
            ->will($this->returnValue($this->runner));

        $runner->expects($this->any())
            ->method('readOutput')
            ->will($this->returnValue(''));

        $this->assertInstanceOf(
            'EssentialsBundle\Shell\Command\Runner',
            $runner->run(new Command("ls"))
        );
    }
}
