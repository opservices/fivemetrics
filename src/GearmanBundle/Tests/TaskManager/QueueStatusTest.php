<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 02/03/17
 * Time: 19:24
 */

namespace GearmanBundle\Tests\TaskManager;

use GearmanBundle\TaskManager\QueueStatus;
use PHPUnit\Framework\TestCase;

/**
 * Class QueueStatusTest
 * @package GearmanBundle\Tests\TaskManager
 */
class QueueStatusTest extends TestCase
{
    /**
     * @var QueueStatus
     */
    protected $qStatus;

    public function setUp()
    {
        $this->qStatus = new QueueStatus(
            'test',
            5,
            10,
            10
        );
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals(
            'test',
            $this->qStatus->getQueueName()
        );

        $this->assertEquals(
            5,
            $this->qStatus->getJobsWaiting()
        );

        $this->assertEquals(
            10,
            $this->qStatus->getJobsRunning()
        );

        $this->assertEquals(
            10,
            $this->qStatus->getAvailableWorkers()
        );
    }
}
