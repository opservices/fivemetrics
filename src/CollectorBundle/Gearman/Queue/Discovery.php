<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 18/10/17
 * Time: 14:49
 */

namespace CollectorBundle\Gearman\Queue;

use CollectorBundle\Collect\CollectBucket;
use CollectorBundle\Collector\ResultSender;
use CollectorBundle\Processor\Processor;
use DataSourceBundle\Gearman\Queue\QueueAbstract;
use EssentialsBundle\Cache\CacheFactory;
use EssentialsBundle\KernelLoader;
use GearmanBundle\Collection\Worker\QueueCollection;
use GearmanBundle\Job\Job;
use GearmanBundle\Worker\Queue;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Discovery extends QueueAbstract
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var ResultSender
     */
    protected $sender;

    /**
     * @var Processor $processor
     */
    protected $processor;

    public function __construct(
        $jobServers = null,
        $worker = null,
        $configuration = null,
        $errorDispatcher = null,
        ContainerInterface $container = null,
        ResultSender $sender = null,
        Processor $processor = null
    ) {
        parent::__construct($jobServers, $worker, $configuration, $errorDispatcher);
        $this->container = (is_null($container))
            ? KernelLoader::load()->getContainer()
            : $container;

        $this->sender = (is_null($sender))
            ? $this->container->get('collect.result.sender')
            : $sender;

        $this->processor = (is_null($processor))
            ? $this->container->get('collect.processor')
            : $processor;
    }

    /**
     * @inheritDoc
     */
    public function getJobType(): string
    {
        return Job::class;
    }

    /**
     * @inheritDoc
     */
    protected function getQueues(): QueueCollection
    {
        return new QueueCollection([
            new Queue('discovery', 'process'),
        ]);
    }

    protected function logStartDiscovery(): Discovery
    {
        /** @var CollectBucket $bucket */
        $bucket  = $this->getJob()->getData();
        $account = $this->getJob()->getAccount();
        $logger  = $this->getLogger();

        $ds = array_map(function (array $collect) {
            return $collect['dataSource']['name'];
        }, $bucket->getCollects()->toArray());

        $msg = sprintf(
            'Starting discovery for account "%s" with data sources: "%s".',
            $account->getEmail(),
            implode('", "', $ds)
        );

        $logger->log(Logger::INFO, $msg);

        return $this;
    }

    public function process(\GearmanJob $job)
    {
        $this->setJob(unserialize($job->workload()));

        try {
            $this->logStartDiscovery();

            /** @var CollectBucket $bucket */
            $bucket = $this->getJob()->getData();
            $this->processor->process($bucket);

            $account = $this->getJob()->getAccount();

            $cache = $this->container->get('cache.factory')
                ->factory($account, 'local_cache');

            $cache->save($job->unique(), $bucket);
        } catch (\Throwable $e) {
            $this->errorDispatcher->send($e, $this->getJob());
        }

        $this->job = null;
        gc_collect_cycles();
    }
}
