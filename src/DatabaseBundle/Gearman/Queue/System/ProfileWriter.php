<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 30/11/17
 * Time: 13:36
 */

namespace DatabaseBundle\Gearman\Queue\System;

use DatabaseBundle\Gearman\Queue\QueueAbstract;
use DatabaseBundle\NoSql\Metric\MetricRepository;
use EssentialsBundle\Entity\Account\AccountInterface;
use EssentialsBundle\Exception\Exceptions;
use EssentialsBundle\KernelLoader;
use GearmanBundle\Collection\Worker\QueueCollection;
use GearmanBundle\Job\Job;
use GearmanBundle\Worker\Queue;

class ProfileWriter extends QueueAbstract
{
    protected function getQueues(): QueueCollection
    {
        return new QueueCollection(
            [ new Queue('profile-writer', 'process') ]
        );
    }

    /**
     * @return string
     */
    public function getJobType(): string
    {
        return Job::class;
    }

    /**
     * @param string $name
     * @return object
     */
    public function getContainer(string $name)
    {
        return KernelLoader::load()
            ->getContainer()
            ->get($name);
    }

    /**
     * @param AccountInterface $account
     * @return bool
     */
    protected function verifyAccountPermission(AccountInterface $account)
    {
        if ($account->getUid() == 'system') {
            return true;
        }

        throw new \InvalidArgumentException(
            "Only system user is able to send a job to profile-writer queue."
            . " Blocked account uid: " . $account->getUid(),
            Exceptions::VALIDATION_ERROR
        );
    }

    /**
     * @param \GearmanJob $job
     */
    public function process(\GearmanJob $job)
    {
        try {
            $this->setJob(unserialize($job->workload()));

            $account = $this->getJob()->getAccount();
            $this->verifyAccountPermission($account);

            /** @var MetricRepository $repository */
            $repository = $this->getContainer('nosql.metric.repository');
            $databaseId = $account->getUid();

            $repository->putMetrics(
                $databaseId,
                $this->getJob()->getData()
            );

        } catch (\Throwable $e) {
            $this->errorDispatcher->send($e);
        }

        $repository = null;
        $account    = null;
        $this->job  = null;

        gc_collect_cycles();
    }
}
