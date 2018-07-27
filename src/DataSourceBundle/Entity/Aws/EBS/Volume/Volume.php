<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 8/8/17
 * Time: 11:37 AM
 */

namespace DataSourceBundle\Entity\Aws\EBS\Volume;

use DataSourceBundle\Collection\Aws\EBS\Attachment\AttachmentCollection;
use DataSourceBundle\Collection\Aws\Tag\TagCollection;
use DataSourceBundle\Entity\Aws\EntityAbstract;
use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\Exception\Exceptions;

/**
 * Class Volume
 * @package DataSourceBundle\Entity\Aws\EBS\Volume
 */
class Volume extends EntityAbstract
{

    protected const STATE_TYPES = [
        "creating",
        "available",
        "in-use",
        "deleting",
        "deleted",
        "error"
    ];

    protected const VOLUME_TYPES = [
        "standard",
        "io1",
        "gp2",
        "sc1",
        "st1"
    ];

    /**
     * @var string
     */
    protected $availabilityZone;

    /**
     * @var DateTime
     */
    protected $createTime;

    /**
     * @var bool
     */
    protected $encrypted;

    /**
     * @var int
     */
    protected $iops;

    /**
     * @var string
     */
    protected $kmsKeyId;

    /**
     * @var int
     */
    protected $size;

    /**
     * @var string
     */
    protected $snapshotId;

    /**
     * @var string
     */
    protected $state;

    /**
     * @var TagCollection
     */
    protected $tags;

    /**
     * @var string
     */
    protected $volumeId;

    /**
     * @var string
     */
    protected $volumeType;

    /**
     * @var AttachmentCollection
     */
    protected $attachments;

    /**
     * Volume constructor.
     * @param string $availabilityZone
     * @param DateTime $createTime
     * @param bool $encrypted
     * @param int|null $iops
     * @param string|null $kmsKeyId
     * @param int $size
     * @param string|null $snapshotId
     * @param string $state
     * @param TagCollection|null $tags
     * @param string $volumeId
     * @param string $volumeType
     * @param AttachmentCollection|null $attachments
     */
    public function __construct(
        string $availabilityZone,
        DateTime $createTime,
        bool $encrypted,
        int $iops = null,
        string $kmsKeyId = null,
        int $size,
        string $snapshotId = null,
        string $state,
        TagCollection $tags = null,
        string $volumeId,
        string $volumeType,
        AttachmentCollection $attachments = null
    ) {
        $this->availabilityZone = $availabilityZone;
        $this->createTime = $createTime;
        $this->encrypted = $encrypted;
        $this->iops = $iops;
        (is_null($iops)) ?: $this->setIops($iops);
        (is_null($kmsKeyId)) ?: $this->setKmsKeyId($kmsKeyId);
        $this->size = $size;
        (is_null($snapshotId)) ?: $this->setSnapshotId($snapshotId);
        $this->state = $state;
        (is_null($tags)) ?: $this->setTags($tags);
        $this->volumeId = $volumeId;
        $this->volumeType = $volumeType;
        (is_null($attachments)) ?: $this->setAttachments($attachments);
    }

    /**
     * @return string
     */
    public function getAvailabilityZone(): string
    {
        return $this->availabilityZone;
    }

    /**
     * @param string $availabilityZone
     * @return Volume
     */
    public function setAvailabilityZone(string $availabilityZone): Volume
    {
        $this->availabilityZone = $availabilityZone;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreateTime(): DateTime
    {
        return $this->createTime;
    }

    /**
     * @param DateTime $createTime
     * @return Volume
     */
    public function setCreateTime(DateTime $createTime): Volume
    {
        $this->createTime = $createTime;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEncrypted(): bool
    {
        return $this->encrypted;
    }

    /**
     * @param bool $encrypted
     * @return Volume
     */
    public function setEncrypted(bool $encrypted): Volume
    {
        $this->encrypted = $encrypted;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getIops()
    {
        return is_null($this->iops) ? null : $this->iops;
    }

    /**
     * @param int $iops
     * @return Volume
     */
    public function setIops(int $iops): Volume
    {
        $this->iops = $iops;
        return $this;
    }

    /**
     * @return string
     */
    public function getKmsKeyId(): string
    {
        return $this->kmsKeyId;
    }

    /**
     * @param string $kmsKeyId
     * @return Volume
     */
    public function setKmsKeyId(string $kmsKeyId): Volume
    {
        $this->kmsKeyId = $kmsKeyId;
        return $this;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @param int $size
     * @return Volume
     */
    public function setSize(int $size): Volume
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @return string
     */
    public function getSnapshotId(): string
    {
        return $this->snapshotId;
    }

    /**
     * @param string $snapshotId
     * @return Volume
     */
    public function setSnapshotId(string $snapshotId): Volume
    {
        $this->snapshotId = $snapshotId;
        return $this;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @param string $state
     * @return Volume
     */
    public function setState(string $state): Volume
    {
        if (! in_array($state, self::STATE_TYPES)) {
            throw new \InvalidArgumentException(
                'An invalid state type was provided: "' . $state . '""',
                Exceptions::VALIDATION_ERROR
            );
        }
        $this->state = $state;
        return $this;
    }

    /**
     * @return TagCollection
     */
    public function getTags(): TagCollection
    {
        return $this->tags;
    }

    /**
     * @param TagCollection $tags
     * @return Volume
     */
    public function setTags(TagCollection $tags): Volume
    {
        $this->tags = $tags;
        return $this;
    }

    /**
     * @return string
     */
    public function getVolumeId(): string
    {
        return $this->volumeId;
    }

    /**
     * @param string $volumeId
     * @return Volume
     */
    public function setVolumeId(string $volumeId): Volume
    {
        $this->volumeId = $volumeId;
        return $this;
    }

    /**
     * @return string
     */
    public function getVolumeType(): string
    {
        return $this->volumeType;
    }

    /**
     * @param string $volumeType
     * @return Volume
     */
    public function setVolumeType(string $volumeType): Volume
    {
        if (! in_array($volumeType, self::VOLUME_TYPES)) {
            throw new \InvalidArgumentException(
                'An invalid volume type was provided: "' . $volumeType . '""',
                Exceptions::VALIDATION_ERROR
            );
        }
        $this->volumeType = $volumeType;
        return $this;
    }

    /**
     * @return AttachmentCollection
     */
    public function getAttachments(): AttachmentCollection
    {
        return is_null($this->attachments) ? new AttachmentCollection() : $this->attachments;
    }

    /**
     * @param AttachmentCollection $attachments
     * @return Volume
     */
    public function setAttachments(AttachmentCollection $attachments): Volume
    {
        $this->attachments = $attachments;
        return $this;
    }
}
