<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 09/02/17
 * Time: 16:35
 */

namespace DataSourceBundle\Aws\EC2\Measurement\AutoScaling;

use DataSourceBundle\Aws\Measurement;
use DataSourceBundle\Aws\MeasurementInterface;
use DataSourceBundle\Collection\Aws\AutoScaling\AutoScalingGroupCollection;
use DataSourceBundle\Entity\Aws\Region\RegionInterface;
use EssentialsBundle\Entity\DateTime\DateTime;

/**
 * Class MeasurementAbstract
 * @package DataSourceBundle\Aws\EC2\Measurement\AutoScaling
 */
abstract class MeasurementAbstract
    extends \DataSourceBundle\Aws\MeasurementAbstract
    implements MeasurementInterface
{
    /**
     * @var AutoScalingGroupCollection
     */
    protected $autoScalingGroups;

    /**
     * MeasurementAbstract constructor.
     * @param RegionInterface $region
     * @param DateTime $dateTime
     * @param AutoScalingGroupCollection $groups
     */
    public function __construct(
        RegionInterface $region,
        DateTime $dateTime,
        AutoScalingGroupCollection $groups
    ) {
        parent::__construct($region, $dateTime);
        $this->setAutoScalingGroups($groups);
    }

    /**
     * @return AutoScalingGroupCollection
     */
    public function getAutoScalingGroups(): AutoScalingGroupCollection
    {
        return $this->autoScalingGroups;
    }

    /**
     * @param AutoScalingGroupCollection $autoScalingGroups
     * @return $this
     */
    public function setAutoScalingGroups(
        AutoScalingGroupCollection $autoScalingGroups
    ) {
        $this->autoScalingGroups = $autoScalingGroups;
        return $this;
    }

    /**
     * @return array
     */
    protected function getNameParts(): array
    {
        return array_merge(
            parent::getNameParts(),
            [ 'ec2', 'autoscaling' ]
        );
    }

    /**
     * @return array
     */
    protected function getTags(): array
    {
        $tags = parent::getTags();

        $tags[] = [
            'key' => '::fm::region',
            'value' => $this->getRegion()->getCode()
        ];

        return $tags;
    }
}
