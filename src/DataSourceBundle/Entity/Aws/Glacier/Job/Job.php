<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 7/26/17
 * Time: 2:47 PM
 */

namespace DataSourceBundle\Entity\Aws\Glacier\Job;

use DataSourceBundle\Entity\Aws\EntityAbstract;
use DataSourceBundle\Entity\Aws\Glacier\Vault\Vault;
use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\Exception\Exceptions;

/**
 * Class Job
 * @package DataSourceBundle\Entity\Aws\Glacier\Job
 */
class Job extends EntityAbstract
{

    protected const STATUS_TYPES = [
        "InProgress",
        "Succeeded",
        "Failed"
    ];

    /**
     * @var $jobId string
     */
    protected $jobId;

    /**
     * @var $vault Vault
     */
    protected $vault;

    /**
     * @var $action string
     */
    protected $action;

    /**
     * @var $vaultARN string
     */
    protected $vaultARN;

    /**
     * @var $creationDate DateTime
     */
    protected $creationDate;

    /**
     * @var $completed bool
     */
    protected $completed;

    /**
     * @var $statusCode string
     */
    protected $statusCode;

    /**
     * @var $completionDate DateTime
     */
    protected $completionDate;

    /**
     * Job constructor.
     * @param string $jobId
     * @param Vault $vault
     * @param string $action
     * @param string $vaultARN
     * @param DateTime $creationDate
     * @param bool $completed
     * @param string $statusCode
     * @param DateTime|null $completionDate
     */
    public function __construct(
        string $jobId,
        Vault $vault,
        string $action,
        string $vaultARN,
        DateTime $creationDate,
        bool $completed,
        string $statusCode,
        DateTime $completionDate = null
    ) {
        $this->jobId        = $jobId;
        $this->vault        = $vault;
        $this->action       = $action;
        $this->vaultARN     = $vaultARN;
        $this->creationDate = $creationDate;
        $this->completed    = $completed;
        $this->statusCode   = $statusCode;
        (empty($completionDate) ?: $this->setCompletionDate($completionDate));
    }

    /**
     * @return string
     */
    public function getJobId(): string
    {
        return $this->jobId;
    }

    /**
     * @param string $jobId
     * @return Job
     */
    public function setJobId($jobId): Job
    {
        if (is_null($jobId)) {
            throw new \RuntimeException(
                "JobId is null",
                Exceptions::RUNTIME_ERROR
            );
        }
        $this->jobId = $jobId;
        return $this;
    }

    /**
     * @return Vault
     */
    public function getVault(): Vault
    {
        return $this->vault;
    }

    /**
     * @param Vault $vault
     * @return Job
     */
    public function setVault(Vault $vault): Job
    {
        $this->vault = $vault;
        return $this;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @param string $action
     * @return Job
     */
    public function setAction(string $action): Job
    {
        $this->action = $action;
        return $this;
    }

    /**
     * @return string
     */
    public function getVaultARN(): string
    {
        return $this->vaultARN;
    }

    /**
     * @param string $vaultARN
     * @return Job
     */
    public function setVaultARN(string $vaultARN): Job
    {
        $this->vaultARN = $vaultARN;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCreationDate(): DateTime
    {
        return $this->creationDate;
    }

    /**
     * @param DateTime $creationDate
     * @return Job
     */
    public function setCreationDate(DateTime $creationDate): Job
    {
        $this->creationDate = $creationDate;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->completed;
    }

    /**
     * @param bool $completed
     * @return Job
     */
    public function setCompleted(bool $completed): Job
    {
        $this->completed = $completed;
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
     * @return Job
     */
    public function setStatusCode(string $statusCode): Job
    {
        if (! in_array($statusCode, self::STATUS_TYPES)) {
            throw new \InvalidArgumentException(
                'An invalid code status type was provided: "' . $statusCode . '""',
                Exceptions::VALIDATION_ERROR
            );
        }
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getCompletionDate(): DateTime
    {
        return $this->completionDate;
    }

    /**
     * @param DateTime $completionDate
     * @return Job
     */
    public function setCompletionDate(DateTime $completionDate): Job
    {
        $this->completionDate = $completionDate;
        return $this;
    }
}
