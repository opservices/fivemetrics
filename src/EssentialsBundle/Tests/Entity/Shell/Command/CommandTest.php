<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 15/02/17
 * Time: 08:35
 */

namespace EssentialsBundle\Tests\Entity\Shell\Command;

use EssentialsBundle\Collection\Shell\Command\ArgumentCollection;
use EssentialsBundle\Entity\Shell\Command\Argument;
use EssentialsBundle\Entity\Shell\Command\Command;
use PHPUnit\Framework\TestCase;

/**
 * Class CommandTest
 * @package EssentialsBundle\Test\Entity\Shell\Command
 */
class CommandTest extends TestCase
{
    /**
     * @var Command
     */
    protected $cmd;

    public function setUp()
    {
        $this->cmd = new Command("/bin/ls");
    }

    /**
     * @test
     */
    public function getConstructorExecutable()
    {
        $this->assertEquals(
            Command::escapeCommand("/bin/ls"),
            $this->cmd->getExecutable()
        );
    }

    /**
     * @test
     */
    public function getEmptyCommandArguments()
    {
        $this->assertInstanceOf(
            'EssentialsBundle\Collection\Shell\Command\ArgumentCollection',
            $this->cmd->getArguments()
        );

        $this->assertEquals(0, count($this->cmd->getArguments()));
    }

    /**
     * @test
     */
    public function defineNotEmptyArgumentsToConstructor()
    {
        $args = new ArgumentCollection();
        $args->add(new Argument("/root/"));
        $cmd = new Command("/bin/ls", $args);

        $this->assertInstanceOf(
            'EssentialsBundle\Collection\Shell\Command\ArgumentCollection',
            $cmd->getArguments()
        );

        $this->assertEquals(1, count($cmd->getArguments()));
    }

    /**
     * @test
     */
    public function changeCommandValues()
    {
        $args = new ArgumentCollection();
        $args->add(new Argument("/root/"));
        $cmd = new Command("/bin/cat", $args);

        $this->assertEquals(
            Command::escapeCommand("/bin/cat"),
            $cmd->getExecutable()
        );

        $this->assertInstanceOf(
            'EssentialsBundle\Collection\Shell\Command\ArgumentCollection',
            $cmd->getArguments()
        );

        $this->assertEquals(1, count($cmd->getArguments()));
    }

    /**
     * @test
     */
    public function commandToString()
    {
        $args = new ArgumentCollection();
        $args->add(new Argument("/root/"));
        $cmd = new Command("/bin/ls", $args);

        $expected = sprintf(
            "%s %s",
            Command::escapeCommand("/bin/ls"),
            Argument::escapeArgument("/root/")
        );

        $this->assertEquals(
            $expected,
            $cmd->__toString()
        );
    }

    /**
     * @test
     */
    public function addArgument()
    {
        $this->cmd->addArgument(new Argument("-l"));
        $this->assertEquals("'-l'", $this->cmd->getArguments()->at(0));
    }

    /**
     * @test
     */
    public function cloneCommand()
    {
        $this->cmd->addArgument(new Argument("-l"));
        $this->cmd->addArgument(new Argument("-h"));
        $this->assertCount(2, $this->cmd->getArguments());

        $cmd2 = clone($this->cmd);

        $this->assertEquals($this->cmd, $cmd2);
        $this->assertNotSame($this->cmd, $cmd2);
    }
}
