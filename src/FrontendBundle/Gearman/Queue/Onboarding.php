<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 13/11/17
 * Time: 17:20
 */

namespace FrontendBundle\Gearman\Queue;

use CollectorBundle\Collect\Collect;
use CollectorBundle\Collect\CollectBucket;
use CollectorBundle\Collector\ResultSender;
use CollectorBundle\Processor\Processor;
use DatabaseBundle\NoSql\Metric\MetricRepository;
use DataSourceBundle\Controller\Api\V1\DataSourceCollectController;
use DataSourceBundle\Entity\DataSource\DataSourceCollect;
use DataSourceBundle\Gearman\Queue\QueueAbstract;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\Entity\Metric\Metric;
use EssentialsBundle\KernelLoader;
use FrontendBundle\Onboarding\DiscoveryMessageProvider;
use GearmanBundle\Collection\Worker\QueueCollection;
use GearmanBundle\Job\Job;
use GearmanBundle\TaskManager\TaskManager;
use GearmanBundle\Worker\Queue;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class Onboarding extends QueueAbstract
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

    /**
     * @var TaskManager
     */
    protected $tm;

    /**
     * @var DiscoveryMessageProvider
     */
    protected $messageProvider;

    /**
     * @var MetricRepository
     */
    protected $repository;

    /**
     * Onboarding constructor.
     * @param null $jobServers
     * @param null $worker
     * @param null $configuration
     * @param null $errorDispatcher
     * @param ContainerInterface|null $container
     * @param ResultSender|null $sender
     * @param Processor|null $processor
     * @param TaskManager|null $tm
     * @param DiscoveryMessageProvider|null $discoveryMessageProvider
     * @param MetricRepository|null $repository
     */
    public function __construct(
        $jobServers = null,
        $worker = null,
        $configuration = null,
        $errorDispatcher = null,
        ContainerInterface $container = null,
        ResultSender $sender = null,
        Processor $processor = null,
        TaskManager $tm = null,
        DiscoveryMessageProvider $discoveryMessageProvider = null,
        MetricRepository $repository = null
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

        $this->tm = (is_null($tm))
            ? $this->container->get('gearman.taskmanager')
            : $tm;

        $this->messageProvider = (is_null($discoveryMessageProvider))
            ? new DiscoveryMessageProvider($this->container)
            : $discoveryMessageProvider;

        $this->repository = (is_null($repository))
            ? $this->container->get('nosql.metric.repository')
            : $repository;
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
            new Queue('onboarding', 'process'),
        ]);
    }

    /**
     * @return Onboarding
     */
    protected function logStartDiscovery(): Onboarding
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

    /**
     * @param string $email
     * @return Account|null|object
     */
    protected function getAccountEntity(string $email)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')
            ->getManager();

        if (! $em->getConnection()->ping()) {
            $em->getConnection()->close();
            $em->getConnection()->connect();
        }

        return $em->getRepository(Account::class)
            ->findOneBy([ 'email' => $email ]);
    }

    /**
     * @param Account $account
     * @return Onboarding
     */
    protected function updateAccount(Account $account): Onboarding
    {
        $account->setOnboardingDoneAt(new DateTime('now', new \DateTimeZone('UTC')));

        $roles = $account->getRoles();
        $roles = array_filter($roles, function ($role) {
            return ($role != 'ROLE_ALLOW_ONBOARDING');
        });

        $account->setRoles($roles);

        /** @var ObjectManager $em */
        $em = $this->container->get('doctrine')
            ->getManager();
        $em->persist($account);
        $em->flush();

        return $this;
    }

    /**
     * @param CollectBucket $bucket
     * @return Onboarding
     */
    protected function createCollects(Account $account, CollectBucket $bucket): Onboarding
    {
        $content = array_map(function ($collect) {
            return [
                'dataSource' => [
                    'name' => $collect['dataSource']['name']
                ],
                'parameters' => $collect['parameters'],
            ];
        }, $bucket->getCollects()->toArray());

        $request = new Request([], [], [], [], [], [], json_encode($content));

        $controller = new DataSourceCollectController();
        $controller->setContainer($this->container);
        $controller->loginUser($account, $request);
        $controller->createCollectsAction($request);

        unset($controller);

        return $this;
    }

    /**
     * @param Account $account
     * @return Onboarding
     */
    protected function rollback(Account $account): Onboarding
    {
        $account->setOnboardingDoneAt(null);
        $roles = array_unique(array_merge(
            $account->getRoles(),
            [ 'ROLE_ALLOW_ONBOARDING' ]
        ));

        $account->setRoles($roles);

        /** @var ObjectManager $em */
        $em = $this->container->get('doctrine')
            ->getManager();

        $collects = $em->getRepository(DataSourceCollect::class)
            ->findBy([ 'account' => $account ]);

        foreach ($collects as $collect) {
            $em->remove($collect);
        }

        $em->flush();

        return $this;
    }

    /**
     * @param CollectBucket $bucket
     */
    protected function setCollectIds(Account $account, CollectBucket $bucket)
    {
        /** @var ObjectRepository $repo */
        $repo = $this->container->get('doctrine')
            ->getRepository(DataSourceCollect::class);

        $collects = $repo->findBy(['account' => $account]);

        $bucketCollects = $bucket->getCollects();
        foreach ($bucketCollects as $bucketCollect) {
            /** @var \CollectorBundle\Collect\Discovery\Collect $bucketCollect */
            /** @var DataSourceCollect $collect */
            foreach ($collects as $collect) {
                if ($bucketCollect->equals($collect)) {
                    $bucketCollect->setId($collect->getId());
                    break;
                }
            }
        }

        return $this;
    }

    protected function waitWriteFinish(Account $account, CollectBucket $bucket)
    {
        $done = [];
        $collects = $bucket->getCollects();
        $query = [
            'period' => 'last5minutes',
            'query' => [],
        ];
        $queries = [];

        do {
            sleep(1);

            /** @var Collect $collect */
            foreach ($collects as $collect) {
                $metrics = $collect->getMetrics();
                /** @var Metric $metric */
                foreach ($metrics as $metric) {
                    $key = md5($metric->getName() . json_encode($metric->getTags()));
                    if ($done[$key]) {
                        continue;
                    }

                    if (! isset($queries[$key])) {
                        $queries[$key] = array_reduce(
                            $metric->getTags()->toArray(),
                            function ($carry, $item) {
                                $carry[$item['key']] = [$item['value']];
                                return $carry;
                            },
                            []
                        );
                    }

                    $query['query']['filter'] = $queries[$key];

                    $result = $this->repository->getHistory(
                        $account->getUid(),
                        $metric->getName(),
                        $query
                    );

                    $done[$key] = (count($result['series'][0]['points']) >= count($metric->getPoints()));
                }
            }
        } while (in_array(false, array_values($done)));
    }

    public function process(\GearmanJob $job)
    {
        $this->setJob(unserialize($job->workload()));
        $account = null;

        try {
            $this->logStartDiscovery();

            /** @var CollectBucket $bucket */
            $bucket = $this->getJob()->getData();
            $this->processor->process($bucket);
            $email = $this->getJob()->getAccount()->getEmail();
            $account = $this->getAccountEntity($email);
            $this->createCollects($account, $bucket);

            // At this moment the onboarding need to save the discovery data,
            // so it's need to have all collect ids defined inside the bucket.
            $this->setCollectIds($account, $bucket);
            $this->sender->send($bucket);
            $this->waitWriteFinish($account, $bucket);
            $this->updateAccount($account);

            $this->tm->runBackground('mail-sender', serialize(
                $this->messageProvider->getMailMessage($account)
            ));

            $cache = $this->container->get('cache.factory')
                ->factory($account, 'local_cache');

            $cache->save($job->unique(), $bucket);
        } catch (\Throwable $e) {
            $this->errorDispatcher->send($e, $this->getJob());
            (is_null($account)) ?: $this->rollback($account);
        }

        $this->job = null;
        gc_collect_cycles();
    }
}
