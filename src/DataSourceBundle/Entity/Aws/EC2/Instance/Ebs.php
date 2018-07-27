<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 20/01/17
 * Time: 15:38
 */

namespace DataSourceBundle\Entity\Aws\EC2\Instance;

use DataSourceBundle\Entity\Aws\EntityAbstract;
use EssentialsBundle\Entity\DateTime\DateTime;

/**
 * Class Ebs
 * @package DataSourceBundle\Entity\Aws\EC2\Instance
 */
class Ebs extends EntityAbstract
{
    const STATUS_TYPES = [
        'attaching',
        'attached',
        'detaching',
        'detached',
        'busy'
    ];

    /**
     * @var DateTime
     */
    protected $attachTime;

    /**
     * @var bool
     */
    protected $deleteOnTermination;

    /**
     * @var string
     */
    protected $volumeId;

    /**
     * @var string
     */
    protected $status;

    /**
     * Ebs constructor.
     * @param DateTime $attachTime
     * @param bool $deleteOnTermination
     * @param string $volumeId
     * @param string $status
     */
    public function __construct(
        DateTime $attachTime,
        bool $deleteOnTermination,
        string $volumeId,
        string $status
    ) {
        $this->setAttachTime($attachTime)
            ->setDeleteOnTermination($deleteOnTermination)
            ->setStatus($status)
            ->setVolumeId($volumeId);
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
     * @return Ebs
     */
    public function setAttachTime(DateTime $attachTime): Ebs
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
     * @return Ebs
     */
    public function setDeleteOnTermination(bool $deleteOnTermination): Ebs
    {
        $this->deleteOnTermination = $deleteOnTermination;
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
     * @return Ebs
     */
    public function setVolumeId(string $volumeId): Ebs
    {
        $this->volumeId = $volumeId;
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
     * @return Ebs
     */
    public function setStatus(string $status): Ebs
    {
        if (! in_array($status, self::STATUS_TYPES)) {
            throw new \InvalidArgumentException(
                'An invalid status has been provided: "' . $status . '"'
            );
        }

        $this->status = $status;
        return $this;
    }
}
