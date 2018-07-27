<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 16/02/17
 * Time: 08:52
 */

namespace DataSourceBundle\Tests\Entity\Aws\AutoScaling;

use DataSourceBundle\Entity\Aws\AutoScaling\SuspendedProcess;
use PHPUnit\Framework\TestCase;

/**
 * Class SuspendedProcessTest
 * @package Test\Entity\Aws\AutoScaling
 */
class SuspendedProcessTest extends TestCase
{
    /**
     * @var SuspendedProcess
     */
    protected $suspendedProcess;

    public function setUp()
    {
        $this->suspendedProcess = new SuspendedProcess(
            "processName",
            "suspensionReason"
        );
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals(
            "processName",
            $this->suspendedProcess->getProcessName()
        );

        $this->assertEquals(
            "suspensionReason",
            $this->suspendedProcess->getSuspensionReason()
        );
    }

    /**
     * @test
     */
    public function setProcessName()
    {
        $this->suspendedProcess->setProcessName("processName.test");

        $this->assertEquals(
            "processName.test",
            $this->suspendedProcess->getProcessName()
        );
    }

    /**
     * @test
     */
    public function setSuspensionReason()
    {
        $this->suspendedProcess->setSuspensionReason("suspensionReason.test");

        $this->assertEquals(
            "suspensionReason.test",
            $this->suspendedProcess->getSuspensionReason()
        );
    }
}
