<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 8/8/17
 * Time: 1:55 PM
 */

namespace DataSourceBundle\Entity\Aws\EBS\Attachment;

use DataSourceBundle\Collection\Aws\EC2\Instance\InstanceCollection;
use DataSourceBundle\Entity\Aws\EC2\Instance\Instance;
use DataSourceBundle\Entity\Aws\EntityAbstract;
use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\Exception\Exceptions;

/**
 * Class Attachment
 * @package DataSourceBundle\Entity\Aws\EBS\Attachment
 */
class Attachment extends EntityAbstract
{

    protected const STATE_TYPES = [
        "attaching",
        "attached",
        "detaching",
        "detached",
        "busy",
    ];

    /**
     * @var DateTime
     */
    protected $attachTime;

    /**
     * @var string
     */
    protected $instanceId;

    /**
     * @var string
     */
    protected $volumeId;

    /**
     * @var string
     */
    protected $state;

    /**
     * @var bool
     */
    protected $deleteOnTermination;

    /**
     * @var string
     */
    protected $device;

    /**
     * @var Instance
     */
    protected $instance;

    /**
     * Attachment constructor.
     * @param DateTime $attachTime
     * @param string $instanceId
     * @param string $volumeId
     * @param string $state
     * @param bool $deleteOnTermination
     * @param string $device
     * @param Instance|null $instance
     */
    public function __construct(
        DateTime $attachTime,
        string $instanceId,
        string $volumeId,
        string $state,
        bool $deleteOnTermination,
        string $device,
        Instance $instance = null
    ) {
        $this->attachTime = $attachTime;
        $this->instanceId = $instanceId;
        $this->volumeId = $volumeId;
        $this->state = $state;
        $this->deleteOnTermination = $deleteOnTermination;
        $this->device = $device;
        (is_null($instance)) ?: $this->setInstance($instance);
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
     * @return string
     */
    public function getInstanceId(): string
    {
        return $this->instanceId;
    }

    /**
     * @param string $instanceId
     * @return Attachment
     */
    public function setInstanceId(string $instanceId): Attachment
    {
        $this->instanceId = $instanceId;
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
     * @return Attachment
     */
    public function setVolumeId(string $volumeId): Attachment
    {
        $this->volumeId = $volumeId;
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
     * @return Attachment
     */
    public function setState(string $state): Attachment
    {
        if (! in_array($state, self::STATE_TYPES)) {
            throw new \InvalidArgumentException(
                'An invalid volume type was provided: "' . $state . '""',
                Exceptions::VALIDATION_ERROR
            );
        }
        $this->state = $state;
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

    /**
     * @return string
     */
    public function getDevice(): string
    {
        return $this->device;
    }

    /**
     * @param string $device
     * @return Attachment
     */
    public function setDevice(string $device): Attachment
    {
        $this->device = $device;
        return $this;
    }

    /**
     * @return null|Instance
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * @param Instance $instance
     * @return Attachment
     */
    public function setInstance(Instance $instance): Attachment
    {
        $this->instance = $instance;
        return $this;
    }
}
