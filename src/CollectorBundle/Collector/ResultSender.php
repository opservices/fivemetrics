<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 19/10/17
 * Time: 09:23
 */

namespace CollectorBundle\Collector;

use CollectorBundle\Collect\Collect;
use CollectorBundle\Collect\CollectBucket;
use DatabaseBundle\Gearman\Queue\CollectResult\Job;
use DatabaseBundle\RealTime\Storage;
use Doctrine\Common\Cache\CacheProvider;
use EssentialsBundle\Cache\CacheFactory;
use EssentialsBundle\Entity\Account\AccountInterface;
use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\Entity\Metric\RealTimeData;
use EssentialsBundle\MutexProvider\MutexProvider;
use GearmanBundle\TaskManager\TaskManager;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Monolog\Logger;

class ResultSender
{
    /**
     * @var TaskManager
     */
    protected $taskManager;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var CacheFactory
     */
    protected $cacheFactory;

    /**
     * @var MutexProvider
     */
    protected $mutexProvider;

    /**
     * ResultSender constructor.
     * @param LoggerInterface $logger
     * @param TaskManager $taskManager
     * @param CacheFactory $cacheFactory
     * @param MutexProvider $mutex
     */
    public function __construct(
        LoggerInterface $logger,
        TaskManager $taskManager,
        CacheFactory $cacheFactory,
        MutexProvider $mutexProvider
    ) {
        $this->logger = $logger;
        $this->taskManager = $taskManager;
        $this->cacheFactory = $cacheFactory;
        $this->mutexProvider = $mutexProvider;
    }

    protected function getMutex(string $lockName)
    {
        return $this->mutexProvider->getMutexInstance($lockName);
    }

    /**
     * @param Collect $collect
     * @param CacheProvider $cache
     * @throws \malkusch\lock\exception\LockAcquireException
     * @throws \malkusch\lock\exception\LockReleaseException
     */
    protected function updateRealTimeData(Collect $collect, CacheProvider $cache)
    {
        $storage = new Storage($cache);
        $realTimeData = $collect->getRealTimeData();

        foreach ($realTimeData as $data) {
            $storage->persist($data);
        }

        foreach ($realTimeData as $realTime) {
            /** @var RealTimeData $realTime */
            $mutex = $this->getMutex($realTime->getMutexKeyName());
            $mutex->synchronized(function () use ($storage) {
                $storage->flush();
            });
        }
    }

    /**
     * @param CollectBucket $bucket
     * @throws \malkusch\lock\exception\LockAcquireException
     * @throws \malkusch\lock\exception\LockReleaseException
     */
    public function send(CollectBucket $bucket)
    {
        $this->logger->log(Logger::INFO, 'Sending result...');
        /** @var CollectBucket $bucket */
        $collects = $bucket->getCollects();
        $account = $bucket->getAccount();

        $this->logger->log(Logger::INFO, 'Account: ' . $account->getUid());
        $cache = $this->cacheFactory->factory($account);

        foreach ($collects as $collect) {
            /** @var Collect $collect */
            $this->updateRealTimeData($collect, $cache);
            $this->sendToNoSQL($collect, $account, $bucket->getTime());
        }

        $this->logger->log(Logger::INFO, 'Send result is done.');
    }

    /**
     * @param Collect $collect
     * @param AccountInterface $account
     * @param DateTime $time
     * @return ResultSender
     */
    protected function sendToNoSQL(
        Collect $collect,
        AccountInterface $account,
        DateTime $time
    ): ResultSender {
        $msg = sprintf(
            'Collect ID: %d Data source: %s Metrics: %d',
            $collect->getId(),
            $collect->getDataSource()->getName(),
            count($collect->getMetrics())
        );

        $this->logger->log(Logger::INFO, $msg);
        $job = serialize(new Job(
            $account,
            $collect->getId(),
            $time,
            $collect->getMetrics()
        ));

        $this->taskManager->runBackground('active-collect-result', $job);

        return $this;
    }
}
