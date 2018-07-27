<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 8/15/17
 * Time: 8:43 AM
 */

namespace DataSourceBundle\Aws\EC2\Measurement\EBS;

use DataSourceBundle\Aws\MeasurementInterface;
use DataSourceBundle\Collection\Aws\EBS\Volume\VolumeCollection;
use DataSourceBundle\Entity\Aws\EBS\Volume\Volume;
use DataSourceBundle\Entity\Aws\EC2\Instance\Instance;
use DataSourceBundle\Entity\Aws\Region\RegionInterface;
use DataSourceBundle\Entity\Aws\Tag\Tag;
use EssentialsBundle\Entity\DateTime\DateTime;

/**
 * Class MeasurementAbstract
 * @package DataSourceBundle\Aws\EC2\Measurement\EBS
 */
abstract class MeasurementAbstract extends \DataSourceBundle\Aws\MeasurementAbstract implements MeasurementInterface
{
    /**
     * @var VolumeCollection
     */
    protected $volumes;

    /**
     * MeasurementAbstract constructor.
     * @param RegionInterface $region
     * @param DateTime $dateTime
     * @param VolumeCollection $volumeCollection
     */
    public function __construct(
        RegionInterface $region,
        DateTime $dateTime,
        VolumeCollection $volumeCollection
    ) {
        parent::__construct($region, $dateTime);
        $this->setVolumes($volumeCollection);
    }

    /**
     * @return array
     */
    protected function getNameParts(): array
    {
        return array_merge(
            parent::getNameParts(),
            ['ec2', 'ebs']
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

    /**
     * @return VolumeCollection
     */
    public function getVolumes(): VolumeCollection
    {
        return $this->volumes;
    }

    /**
     * @param VolumeCollection $volumes
     */
    public function setVolumes(VolumeCollection $volumes)
    {
        $this->volumes = $volumes;
    }

    /**
     * @param Volume $volume
     * @param string $instanceName
     * @return array
     */
    protected function getMeasurementTags(Volume $volume, string $instanceName): array
    {
        $tags = parent::getTags();

        $tags[] = [
            'key' => '::fm::state',
            'value' => $volume->getState()
        ];

        $tags[] = [
            'key' => '::fm::type',
            'value' => $volume->getVolumeType()
        ];

        $tags[] = [
            'key' => '::fm::availabilityZone',
            'value' => $volume->getAvailabilityZone()
        ];

        $tags[] = [
            'key' => '::fm::instanceName',
            'value' => $instanceName
        ];

        return $this->mergeTags($tags, $volume->getTags()->toArray());
    }

    /**
     * @param Instance $instance
     * @return string
     */
    protected function getInstanceTagName(Instance $instance)
    {
        /**
         * @var $tag Tag
         */
        foreach ($instance->getTags() as $tag) {
            if ($tag->getKey() == 'Name') {
                return $tag->getValue();
            }
        }
        return "";
    }

    /**
     * @param array $myTags
     * @param array $awsTags
     * @return array
     */
    protected function mergeTags(array $myTags, array $awsTags): array
    {
        array_map(function ($tag) use (&$myTags) {
            $myTags[] = [
                'key' => $tag['Key'],
                'value' => $tag['Value'],
            ];
        }, $awsTags);

        return $myTags;
    }
}
