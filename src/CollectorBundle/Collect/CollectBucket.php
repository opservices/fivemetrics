<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 25/09/17
 * Time: 14:54
 */

namespace CollectorBundle\Collect;

use EssentialsBundle\Entity\Account\AccountInterface;
use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\Entity\EntityAbstract;

class CollectBucket extends EntityAbstract
{
    /**
     * @var AccountInterface
     */
    protected $account;

    /**
     * @var DateTime
     */
    protected $time;

    /**
     * @var CollectCollection
     */
    protected $collects;

    /**
     * Collect constructor.
     * @param AccountInterface $account
     * @param DateTime $time
     * @param CollectCollection $collects
     */
    public function __construct(AccountInterface $account, DateTime $time, CollectCollection $collects)
    {
        $this->setAccount($account)
            ->setTime($time)
            ->setCollects($collects);
    }

    /**
     * @return AccountInterface
     */
    public function getAccount(): AccountInterface
    {
        return $this->account;
    }

    /**
     * @param AccountInterface $account
     * @return Collect
     */
    public function setAccount(AccountInterface $account): CollectBucket
    {
        $this->account = $account;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getTime(): DateTime
    {
        return $this->time;
    }

    /**
     * @param DateTime $time
     * @return Collect
     */
    public function setTime(DateTime $time): CollectBucket
    {
        $this->time = $time;
        return $this;
    }

    /**
     * @return CollectCollection
     */
    public function getCollects(): CollectCollection
    {
        return $this->collects;
    }

    /**
     * @param CollectCollection $collects
     * @return Collect
     */
    public function setCollects(CollectCollection $collects): CollectBucket
    {
        $this->collects = $collects;
        return $this;
    }

    public function convertCollectsToArray()
    {
        return $this->getCollects()->toArray();
    }

    public function mapCollects(\Closure $closure)
    {
        return array_map($closure, $this->convertCollectsToArray());
    }
}
