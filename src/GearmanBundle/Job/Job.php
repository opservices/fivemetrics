<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 03/03/17
 * Time: 08:56
 */

namespace GearmanBundle\Job;

use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Entity\Account\AccountInterface;
use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\Entity\EntityAbstract;
use EssentialsBundle\Profiler\Profiler;

/**
 * Class Job
 * @package GearmanBundle\Job
 */
class Job extends EntityAbstract implements JobInterface
{
    /**
     * @var AccountInterface
     */
    protected $account;

    /**
     * @var DateTime
     */
    protected $datetime;

    protected $data;

    /**
     * @var Profiler
     */
    protected $profiler = null;

    /**
     * Job constructor.
     * @param AccountInterface $account
     * @param DateTime|null $datetime
     * @param mixed $data
     */
    public function __construct(
        AccountInterface $account,
        DateTime $datetime = null,
        $data = null,
        Profiler $profiler = null
    ) {
        $dt = $datetime ?? new DateTime('now', new \DateTimeZone('UTC'));

        $this->setAccount($account)
            ->setDateTime($dt)
            ->setData($data);

        (is_null($profiler)) ?: $this->setProfiler($profiler);
    }

    /**
     * Constructor
     *
     * Creates a job using just account and data parameters
     *
     * @param AccountInterface $account
     * @param mixed $data
     * @return Job
     */
    public static function createFromAccount(AccountInterface $account, $data = null): Job
    {
        return new static($account, null, $data);
    }

    /**
     * @return Account
     */
    public function getAccount(): AccountInterface
    {
        return $this->account;
    }

    /**
     * @param AccountInterface $account
     * @return Job
     */
    public function setAccount(AccountInterface $account): Job
    {
        $this->account = $account;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param $data
     * @return Job
     */
    public function setData($data): Job
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDateTime(): DateTime
    {
        return $this->datetime;
    }

    /**
     * @param DateTime $datetime
     * @return Job
     */
    public function setDateTime(DateTime $datetime): Job
    {
        $this->datetime = $datetime;
        return $this;
    }

    /**
     * @return Profiler|null
     */
    public function getProfiler()
    {
        return $this->profiler;
    }

    /**
     * @param Profiler $profiler
     * @return JobInterface
     */
    public function setProfiler(Profiler $profiler): JobInterface
    {
        $this->profiler = $profiler;
        return $this;
    }
}
