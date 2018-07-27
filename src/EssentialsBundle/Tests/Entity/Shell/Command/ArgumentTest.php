<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 15/02/17
 * Time: 08:13
 */

namespace EssentialsBundle\Tests\Entity\Shell\Command;

use EssentialsBundle\Entity\Shell\Command\Argument;
use PHPUnit\Framework\TestCase;

/**
 * Class ArgumentTest
 * @package EssentialsBundle\Test\Entity\Shell\Command
 */
class ArgumentTest extends TestCase
{
    /**
     * @var Argument
     */
    protected $arg;

    public function setUp()
    {
        $this->arg = new Argument("--test");
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals(
            Argument::escapeArgument("--test"),
            $this->arg->getName()
        );

        $this->assertEquals(
            null,
            $this->arg->getValue()
        );
    }

    /**
     * @test
     */
    public function overwriteConstructorParameters()
    {
        $this->arg->setName("--unit-test");
        $this->arg->setValue("test");

        $this->assertEquals(
            Argument::escapeArgument("--unit-test"),
            $this->arg->getName()
        );

        $this->assertEquals(
            Argument::escapeArgument("test"),
            $this->arg->getValue()
        );
    }

    /**
     * @test
     */
    public function argumentToString()
    {
        $this->assertEquals(
            Argument::escapeArgument("--test"),
            $this->arg->__toString()
        );

        $this->arg->setValue("test");

        $expected = sprintf(
            "%s %s",
            Argument::escapeArgument("--test"),
            Argument::escapeArgument("test")
        );

        $this->assertEquals(
            $expected,
            $this->arg->__toString()
        );
    }

    /**
     * @test
     */
    public function defineValueOnConstructor()
    {
        $arg = new Argument("--parameter", "parameter value");
        $expected = sprintf(
            "%s %s",
            Argument::escapeArgument("--parameter"),
            Argument::escapeArgument("parameter value")
        );

        $this->assertEquals(
            $expected,
            $arg->__toString()
        );
    }
}
