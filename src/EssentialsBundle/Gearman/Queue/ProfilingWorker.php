<?php

namespace EssentialsBundle\Gearman\Queue;

use EssentialsBundle\Entity\Account\AccountInterface;
use EssentialsBundle\Exception\Dispatcher;
use EssentialsBundle\Profiler\Analyzer\AnalyzerInterface;
use EssentialsBundle\Profiler\Analyzer\Job as JobAnalyzer;
use EssentialsBundle\Profiler\Profiler;
use GearmanBundle\Collection\Configuration\JobServerCollection;
use GearmanBundle\Collection\Worker\QueueCollection as Queues;
use GearmanBundle\TaskManager\TaskManager;
use GearmanBundle\Worker\Queue;
use GearmanBundle\Worker\WorkerAbstract;
use GearmanBundle\Job\Job;
use EssentialsBundle\KernelLoader;
use EssentialsBundle\Entity\Account\Account;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ProfilingWorker extends WorkerAbstract
{
    /**
     * System Account
     *
     * @var Account
     */
    protected $systemAccount = null;

    /**
     * Dependency Injection Container
     * @var ContainerInterface
     */
    protected $diContainer;

    /**
     * @var JobAnalyzer
     */
    protected $analyzer;

    /**
     * ProfilingWorker constructor.
     * @param JobServerCollection|null $jobServers
     * @param \GearmanWorker|null $worker
     * @param null $configuration
     * @param Dispatcher|null $errorDispatcher
     * @param ContainerInterface|null $container
     * @param JobAnalyzer|null $analyzer
     */
    public function __construct(
        JobServerCollection $jobServers = null,
        \GearmanWorker $worker = null,
        $configuration = null,
        Dispatcher $errorDispatcher = null,
        ContainerInterface $container = null,
        JobAnalyzer $analyzer = null
    ) {
        parent::__construct($jobServers, $worker, $configuration, $errorDispatcher);
        $this->diContainer = $container ?? KernelLoader::load()->getContainer();
        $this->analyzer = $analyzer ?? new JobAnalyzer();
    }

    protected function getQueues(): Queues
    {
        return new Queues([new Queue('profiling', 'process')]);
    }

    protected function getProfiler(\GearmanJob $job)
    {
        /** @var Job $appJob */
        $appJob = unserialize($job->workload());

        if (! is_a($appJob, Job::class)) {
            return null;
        }

        /** @var Profiler $profiler */
        $profiler = $appJob->getData();

        if ($profiler instanceof Profiler) {
            $profiler->disableEvents();
            return $profiler;
        }

        return null;
    }

    /**
     * @return TaskManager
     */
    protected function getTaskManager(): TaskManager
    {
        return $this->diContainer->get('gearman.taskmanager');
    }

    public function process(
        \GearmanJob $job,
        Account $systemAccount = null,
        AnalyzerInterface $analyzer = null
    ) {
        /** @var Profiler $profiler */
        $profiler = $this->getProfiler($job);
        if (is_null($profiler)) {
            return;
        }

        $this->systemAccount = $systemAccount ?? $this->getSystemAccount();

        try {
            $analyzer = $this->analyzer ?: $analyzer;
            $metrics = $analyzer->setProfiler($profiler)
                ->getMetrics();

            if ($metrics->isEmpty()) {
                return;
            }

            $job = Job::createFromAccount(
                $this->systemAccount,
                $metrics
            );

            $this->getTaskManager()->runBackground(
                'profile-writer',
                serialize($job)
            );
        } catch (\Throwable $e) {
            $this->errorDispatcher->send($e);
        }
    }

    protected function getSystemAccount(): AccountInterface
    {
        if (! is_null($this->systemAccount)) {
            return $this->systemAccount;
        }

        $orm = $this->diContainer->get('doctrine');
        $repository = $orm->getRepository(Account::class);
        return $repository->findOneByEmail($this->configuration['accountEmail']);
    }
}
