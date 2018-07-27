<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 8/8/17
 * Time: 3:24 PM
 */

namespace DataSourceBundle\Entity\Aws\EBS\Attachment;

use DataSourceBundle\Collection\Aws\EBS\Attachment\AttachmentCollection;
use EssentialsBundle\Entity\DateTime\DateTime;

/**
 * Class Builder
 * @package DataSourceBundle\Entity\Aws\EBS\Attachment
 */
class Builder
{
    /**
     * @param array $data
     * @param AttachmentCollection|null $attachments
     * @return AttachmentCollection
     */
    public static function build(
        array $data,
        AttachmentCollection $attachments = null
    ): AttachmentCollection {

        if (is_null($attachments)) {
            $attachments = new AttachmentCollection();
        }

        foreach ($data as $attachment) {
            $attachments->add(
                new Attachment(
                    new DateTime($attachment["AttachTime"]),
                    $attachment["InstanceId"],
                    $attachment["VolumeId"],
                    $attachment["State"],
                    $attachment["DeleteOnTermination"],
                    $attachment["Device"]
                )
            );
        }
        return $attachments;
    }
}
