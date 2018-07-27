<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 16/02/17
 * Time: 13:49
 */

namespace DataSourceBundle\Tests\Entity\Aws\AutoScaling;

use DataSourceBundle\Entity\Aws\AutoScaling\Activity;
use EssentialsBundle\Entity\DateTime\DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Class ActivityTest
 * @package DataSourceBundle\Test\Entity\Aws\AutoScaling
 */
class ActivityTest extends TestCase
{
    /**
     * @var Activity
     */
    protected $activity;

    public function setUp()
    {
        $this->activity = new Activity(
            "activityId",
            "autoScalingGroupName",
            "description",
            "cause",
            DateTime::createFromFormat('Y-m-d H:i', '2017-02-16 13:51'),
            DateTime::createFromFormat('Y-m-d H:i', '2017-02-16 13:52'),
            "PendingSpotBidPlacement",
            10,
            "details"
        );
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals(
            "activityId",
            $this->activity->getActivityId()
        );

        $this->assertEquals(
            "autoScalingGroupName",
            $this->activity->getAutoScalingGroupName()
        );

        $this->assertEquals(
            "description",
            $this->activity->getDescription()
        );

        $this->assertEquals(
            "cause",
            $this->activity->getCause()
        );

        $this->assertEquals(
            '2017-02-16 13:51',
            $this->activity->getStartTime()->format('Y-m-d H:i')
        );

        $this->assertEquals(
            '2017-02-16 13:52',
            $this->activity->getEndTime()->format('Y-m-d H:i')
        );

        $this->assertEquals(
            "PendingSpotBidPlacement",
            $this->activity->getStatusCode()
        );

        $this->assertEquals(
            10,
            $this->activity->getProgress()
        );

        $this->assertEquals(
            "details",
            $this->activity->getDetails()
        );
    }

    /**
     * @Stest
     */
    public function setActivityId()
    {
        $this->activity->setActivityId("activityId.test");

        $this->assertEquals(
            "activityId.test",
            $this->activity->getActivityId()
        );
    }

    /**
     * @test
     */
    public function setAutoScalingGroupName()
    {
        $this->activity->setAutoScalingGroupName("autoScalingGroupName.test");

        $this->assertEquals(
            "autoScalingGroupName.test",
            $this->activity->getAutoScalingGroupName()
        );
    }

    /**
     * @test
     */
    public function setDescription()
    {
        $this->activity->setDescription("description.test");

        $this->assertEquals(
            "description.test",
            $this->activity->getDescription()
        );
    }

    /**
     * @test
     */
    public function setCause()
    {
        $this->activity->setCause("cause.test");

        $this->assertEquals(
            "cause.test",
            $this->activity->getCause()
        );
    }

    /**
     * @test
     */
    public function setStartTime()
    {
        $this->activity->setStartTime(
            DateTime::createFromFormat('Y-m-d H:i', '2017-02-16 00:00')
        );

        $this->assertEquals(
            '2017-02-16 00:00',
            $this->activity->getStartTime()->format('Y-m-d H:i')
        );
    }

    /**
     * @test
     */
    public function setEndTime()
    {
        $this->activity->setEndTime(
            DateTime::createFromFormat('Y-m-d H:i', '2017-02-16 01:00')
        );

        $this->assertEquals(
            '2017-02-16 01:00',
            $this->activity->getEndTime()->format('Y-m-d H:i')
        );
    }

    /**
     * @test
     */
    public function setStatusCode()
    {
        $this->activity->setStatusCode("WaitingForSpotInstanceRequestId");

        $this->assertEquals(
            "WaitingForSpotInstanceRequestId",
            $this->activity->getStatusCode()
        );
    }

    /**
     * @test
     * @dataProvider invalidStatusCodes
     * @expectedException \InvalidArgumentException
     */
    public function setStatusCodeInvalid($data)
    {
        $this->activity->setStatusCode($data);
    }

    public function invalidStatusCodes()
    {
        return [
            [ "" ],
            [ "invalid" ]
        ];
    }

    /**
     * @test
     */
    public function setProgress()
    {
        $this->activity->setProgress(20);

        $this->assertEquals(
            20,
            $this->activity->getProgress()
        );
    }

    /**
     * @test
     */
    public function setDetails()
    {
        $this->activity->setDetails("details.test");

        $this->assertEquals(
            "details.test",
            $this->activity->getDetails()
        );
    }
}
