<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 04/02/17
 * Time: 14:46
 */

namespace DataSourceBundle\Entity\Aws\AutoScaling;

use DataSourceBundle\Entity\Aws\EntityAbstract;
use EssentialsBundle\Entity\DateTime\DateTime;

/**
 * Class Activity
 * @package Entity\Aws\AutoScaling
 */
class Activity extends EntityAbstract
{
    const STATUSCODE_TYPES = [
        'PendingSpotBidPlacement',
        'WaitingForSpotInstanceRequestId',
        'WaitingForSpotInstanceId',
        'WaitingForInstanceId',
        'PreInService',
        'InProgress',
        'WaitingForELBConnectionDraining',
        'MidLifecycleAction',
        'WaitingForInstanceWarmup',
        'Successful',
        'Failed',
        'Cancelled'
    ];

    /**
     * @var string
     */
    protected $activityId;

    /**
     * @var string
     */
    protected $autoScalingGroupName;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $cause;

    /**
     * @var DateTime
     */
    protected $startTime;

    /**
     * @var DateTime
     */
    protected $endTime;

    /**
     * @var string
     */
    protected $statusCode;

    /**
     * @var int
     */
    protected $progress;

    /**
     * @var string
     */
    protected $details;

    /**
     * Activity constructor.
     * @param string $activityId
     * @param string $autoScalingGroupName
     * @param string $description
     * @param string $cause
     * @param DateTime $startTime
     * @param DateTime $endTime
     * @param string $statusCode
     * @param int $progress
     * @param string $details
     */
    public function __construct(
        string $activityId,
        string $autoScalingGroupName,
        string $description,
        string $cause,
        DateTime $startTime,
        DateTime $endTime,
        string $statusCode,
        int $progress,
        string $details
    ) {
        $this->setActivityId($activityId)
            ->setAutoScalingGroupName($autoScalingGroupName)
            ->setDescription($description)
            ->setCause($cause)
            ->setStartTime($startTime)
            ->setEndTime($endTime)
            ->setStatusCode($statusCode)
            ->setProgress($progress)
            ->setDetails($details);
    }

    /**
     * @return string
     */
    public function getDetails(): string
    {
        return $this->details;
    }

    /**
     * @param string $details
     * @return Activity
     */
    public function setDetails(string $details): Activity
    {
        $this->details = $details;
        return $this;
    }

    /**
     * @return string
     */
    public function getActivityId(): string
    {
        return $this->activityId;
    }

    /**
     * @param string $activityId
     * @return Activity
     */
    public function setActivityId(string $activityId): Activity
    {
        $this->activityId = $activityId;
        return $this;
    }

    /**
     * @return string
     */
    public function getAutoScalingGroupName(): string
    {
        return $this->autoScalingGroupName;
    }

    /**
     * @param string $autoScalingGroupName
     * @return Activity
     */
    public function setAutoScalingGroupName(string $autoScalingGroupName): Activity
    {
        $this->autoScalingGroupName = $autoScalingGroupName;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Activity
     */
    public function setDescription(string $description): Activity
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getCause(): string
    {
        return $this->cause;
    }

    /**
     * @param string $cause
     * @return Activity
     */
    public function setCause(string $cause): Activity
    {
        $this->cause = $cause;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getStartTime(): DateTime
    {
        return $this->startTime;
    }

    /**
     * @param DateTime $startTime
     * @return Activity
     */
    public function setStartTime(DateTime $startTime): Activity
    {
        $this->startTime = $startTime;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getEndTime(): DateTime
    {
        return $this->endTime;
    }

    /**
     * @param DateTime $endTime
     * @return Activity
     */
    public function setEndTime(DateTime $endTime): Activity
    {
        $this->endTime = $endTime;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatusCode(): string
    {
        return $this->statusCode;
    }

    /**
     * @param string $statusCode
     * @return Activity
     */
    public function setStatusCode(string $statusCode): Activity
    {
        if (! in_array($statusCode, self::STATUSCODE_TYPES)) {
            throw new \InvalidArgumentException(
                'An invalid status code was provided:'
                . ' "' . $statusCode . '""'
            );
        }

        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @return int
     */
    public function getProgress(): int
    {
        return $this->progress;
    }

    /**
     * @param int $progress
     * @return Activity
     */
    public function setProgress(int $progress): Activity
    {
        $this->progress = $progress;
        return $this;
    }
}
