<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 02/03/17
 * Time: 15:48
 */

namespace GearmanBundle\Tests\Worker\Process;

use GearmanBundle\Worker\Process\Status;
use PHPUnit\Framework\TestCase;

/**
 * Class StatusTest
 * @package GearmanBundle\Tests\Worker\Process
 */
class StatusTest extends TestCase
{
    /**
     * @var Status
     */
    protected $status;

    public function setUp()
    {
        $this->status = new Status(
            'command',
            12345,
            true,
            false,
            false,
            false,
            0,
            0
        );
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals(
            'command',
            $this->status->getCommand()
        );

        $this->assertEquals(
            12345,
            $this->status->getPid()
        );

        $this->assertTrue($this->status->isRunning());
        $this->assertFalse($this->status->isSignaled());
        $this->assertFalse($this->status->isStopped());
        $this->assertFalse($this->status->isUndefined());

        $this->assertEquals(
            0,
            $this->status->getExitCode()
        );

        $this->assertEquals(
            0,
            $this->status->getTermSig()
        );
    }
}
