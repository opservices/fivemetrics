<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 29/09/17
 * Time: 09:18
 */

namespace CollectorBundle\Processor;

use CollectorBundle\Collect\Collect;
use CollectorBundle\Collect\CollectBucket;
use DataSourceBundle\Gearman\Queue\Collector\Generic\ResultSet;
use EssentialsBundle\Collection\Metric\RealTimeDataCollection;
use GearmanBundle\Collection\Job\JobCollection;
use GearmanBundle\Job\Job;
use GearmanBundle\TaskManager\TaskManager;
use Symfony\Bridge\Monolog\Logger;

class Processor
{
    /**
     * @var TaskManager
     */
    protected $taskManager;

    /**
     * @var CollectBucket
     */
    protected $bucket = null;

    /**
     * @var array
     */
    protected $runningJobs = [];

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var bool
     */
    protected $isProcessing = false;

    /**
     * Processor constructor.
     * @param TaskManager $taskManager
     */
    public function __construct(
        TaskManager $taskManager,
        Logger $logger
    ) {
        $this->logger = $logger;
        $this->taskManager = $taskManager;
        $this->taskManager->getClient()
            ->setCompleteCallback([$this, "complete"]);
    }

    public function complete(\GearmanTask $task)
    {
        $uid = $task->unique();

        $collectId = $this->runningJobs[$uid]['collect'];
        /** @var CollectBucket $bucket */
        $bucket = $this->runningJobs[$uid]['bucket'];

        /** @var ResultSet $data */
        $data = unserialize($task->data());

        /** @var Collect $collect */
        $collect = $bucket->getCollects()->find($collectId);
        $collect->getPendingJobs()->concat($data->getJobs());
        $collect->getMetrics()->concat($data->getMetrics());

        if ($data->getData() instanceof RealTimeDataCollection) {
            $collect->getRealTimeData()->concat($data->getData());
        }

        (empty($data->getError())) ?: $collect->addError($data->getError());
    }

    protected function prepareTasks(
        CollectBucket $bucket,
        string $collectId,
        int $limit,
        JobCollection $jobs
    ) {
        for ($started = 0; ($started < $limit) && (!$jobs->isEmpty()); $started++) {
            /** @var Job $job */
            $job = $jobs->pop();
            if ($job->getProfiler()) {
                $job->getProfiler()
                    ->resetTimers()
                    ->enableEvents();
            }

            $data = serialize($job);
            $uid = md5($collectId . $data);

            $this->taskManager->getClient()->addTask(
                "collect-processor",
                $data,
                null,
                $uid
            );

            if ($job->getProfiler()) {
                $job->getProfiler()
                    ->disableEvents();
            }

            $this->runningJobs[$uid]['collect'] = $collectId;
            $this->runningJobs[$uid]['bucket'] = $bucket;

            $msg = sprintf(
                'Adding [%d] uid: "%s"%s',
                $started + 1,
                $uid,
                ($this->logger->isHandling(Logger::DEBUG)) ? ' job: ' . json_encode($job) : ''
            );

            ($this->logger->isHandling(Logger::DEBUG))
                ? $this->logger->log(Logger::DEBUG, $msg)
                : $this->logger->log(Logger::INFO, $msg);
        }
    }

    protected function hasPendingJobs(CollectBucket $bucket)
    {
        /** @var CollectBucket $bucket */
        $collects = $bucket->getCollects();

        foreach ($collects as $collect) {
            /** @var Collect $collect */
            if (!$collect->getPendingJobs()->isEmpty()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param CollectBucket $bucket
     * @return Processor
     */
    public function process(CollectBucket $bucket): Processor
    {
        if (! $this->hasPendingJobs($bucket)) {
            return $this;
        }

        return $this->run($bucket);
    }

    protected function run(CollectBucket $bucket): Processor
    {
        $this->runningJobs = [];
        $this->isProcessing = true;
        $collects = $bucket->getCollects();
        $uid = $bucket->getAccount()->getUid();

        foreach ($collects as $collect) {
            /** @var Collect $collect */
            $limit = $collect->getDataSource()->getMaxConcurrency();
            $jobs = $collect->getPendingJobs();

            $msg = sprintf(
                'Account: %s Collect ID: %d Data source: %s Limit: %d Pending jobs: %d',
                $uid,
                $collect->getId(),
                $collect->getDataSource()->getName(),
                $collect->getDataSource()->getMaxConcurrency(),
                count($collect->getPendingJobs())
            );
            $this->logger->log(Logger::INFO, $msg);

            $this->prepareTasks($bucket, $collect->getId(), $limit, $jobs);
        }

        $this->logger->log(Logger::DEBUG, 'Running tasks...');

        if (!$this->taskManager->getClient()->runTasks()) {
            $this->logger->log(Logger::ERROR, $this->taskManager->getClient()->error());
        }

        if ($this->hasPendingJobs($bucket)) {
            $this->run($bucket);
        }

        $this->isProcessing = false;

        return $this;
    }

    /**
     * @return bool
     */
    public function isProcessing(): bool
    {
        return $this->isProcessing;
    }
}
