<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 8/8/17
 * Time: 3:33 PM
 */

namespace DataSourceBundle\Entity\Aws\EBS\Volume;

use DataSourceBundle\Collection\Aws\EBS\Volume\VolumeCollection;
use DataSourceBundle\Entity\Aws\EBS\Attachment\Builder as AttachmentBuilder;
use DataSourceBundle\Entity\Aws\Tag\Builder as TagBuilder;
use EssentialsBundle\Entity\DateTime\DateTime;

/**
 * Class Builder
 * @package DataSourceBundle\Entity\Aws\EBS\Volume
 */
class Builder
{
    /**
     * @param array $data
     * @param VolumeCollection|null $volumes
     * @return VolumeCollection
     */
    public static function build(
        array $data,
        VolumeCollection $volumes = null
    ): VolumeCollection {

        if (is_null($volumes)) {
            $volumes = new VolumeCollection();
        }

        foreach ($data as $volume) {
            $volumes->add(
                new Volume(
                    $volume['AvailabilityZone'],
                    new DateTime($volume['CreateTime']),
                    $volume['Encrypted'],
                    (empty($volume['Iops'])) ? null : $volume['Iops'],
                    empty($volume['KmsKeyId']) ? null : $volume['KmsKeyId'],
                    $volume['Size'],
                    empty($volume['SnapshotId']) ? null : $volume['SnapshotId'],
                    $volume['State'],
                    (empty($volume["Tags"])) ? TagBuilder::build([]) : TagBuilder::build($volume["Tags"]),
                    $volume['VolumeId'],
                    $volume['VolumeType'],
                    (empty($volume["Attachments"])) ? null : AttachmentBuilder::build($volume["Attachments"])
                )
            );
        }
        return $volumes;
    }
}
