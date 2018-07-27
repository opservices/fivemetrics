<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 21/01/17
 * Time: 09:42
 */

namespace DataSourceBundle\Entity\Aws\EC2\NetworkInterface;

use DataSourceBundle\Entity\Aws\EntityAbstract;
use EssentialsBundle\Entity\DateTime\DateTime;

/**
 * Class Attachment
 * @package DataSourceBundle\Entity\Aws\EC2\NetworkInterface
 */
class Attachment extends EntityAbstract
{
    const STATUS_TYPES = [
        'attaching',
        'attached',
        'detaching',
        'detached'
    ];

    /**
     * @var string
     */
    protected $attachmentId;

    /**
     * @var int
     */
    protected $deviceIndex;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var DateTime
     */
    protected $attachTime;

    /**
     * @var bool
     */
    protected $deleteOnTermination;

    /**
     * Attachment constructor.
     * @param string $attachmentId
     * @param int $deviceIndex
     * @param string $status
     * @param DateTime $attachTime
     * @param bool $deleteOnTermination
     */
    public function __construct(
        string $attachmentId,
        int $deviceIndex,
        string $status,
        DateTime $attachTime,
        bool $deleteOnTermination
    ) {
        $this->setAttachmentId($attachmentId)
            ->setAttachTime($attachTime)
            ->setStatus($status)
            ->setDeleteOnTermination($deleteOnTermination)
            ->setDeviceIndex($deviceIndex);
    }

    /**
     * @return string
     */
    public function getAttachmentId(): string
    {
        return $this->attachmentId;
    }

    /**
     * @param string $attachmentId
     * @return Attachment
     */
    public function setAttachmentId(string $attachmentId): Attachment
    {
        $this->attachmentId = $attachmentId;
        return $this;
    }

    /**
     * @return int
     */
    public function getDeviceIndex(): int
    {
        return $this->deviceIndex;
    }

    /**
     * @param int $deviceIndex
     * @return Attachment
     */
    public function setDeviceIndex(int $deviceIndex): Attachment
    {
        $this->deviceIndex = $deviceIndex;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return Attachment
     */
    public function setStatus(string $status): Attachment
    {
        if (! in_array($status, self::STATUS_TYPES)) {
            throw new \InvalidArgumentException(
                'An invalid status type was provided:'
                . ' "' . $status . '"'
            );
        }

        $this->status = $status;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getAttachTime(): DateTime
    {
        return $this->attachTime;
    }

    /**
     * @param DateTime $attachTime
     * @return Attachment
     */
    public function setAttachTime(DateTime $attachTime): Attachment
    {
        $this->attachTime = $attachTime;
        return $this;
    }

    /**
     * @return bool
     */
    public function isDeleteOnTermination(): bool
    {
        return $this->deleteOnTermination;
    }

    /**
     * @param bool $deleteOnTermination
     * @return Attachment
     */
    public function setDeleteOnTermination(bool $deleteOnTermination): Attachment
    {
        $this->deleteOnTermination = $deleteOnTermination;
        return $this;
    }
}
