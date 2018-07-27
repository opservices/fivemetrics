<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 06/06/17
 * Time: 11:05
 */

namespace DatabaseBundle\Gearman\Queue\CollectResult;

use DatabaseBundle\Gearman\Queue\QueueAbstract;
use DatabaseBundle\NoSql\Metric\MetricRepository;
use DataSourceBundle\Entity\DataSource\DataSourceCollect;
use Doctrine\ORM\EntityManager;
use EssentialsBundle\KernelLoader;
use GearmanBundle\Collection\Worker\QueueCollection;
use GearmanBundle\Worker\Queue;

class ActiveCollectWriter extends QueueAbstract
{
    protected function getQueues(): QueueCollection
    {
        return new QueueCollection(
            [ new Queue('active-collect-writer', 'process') ]
        );
    }

    /**
     * @param $job
     * @return bool
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

    protected function updateLastCollectUpdate()
    {
        /** @var EntityManager $em */
        $em = $this->getContainer('doctrine')->getManager();

        if (! $em->getConnection()->ping()) {
            $em->getConnection()->close();
            $em->getConnection()->connect();
        }

        /** @var DataSourceCollect $collect */
        $collect = $em->getRepository(DataSourceCollect::class)
            ->findOneBy([ 'id' => $this->getJob()->getCollectId() ]);

        $collect->setLastUpdate($this->getJob()->getDateTime());
        $em->persist($collect);
        $em->flush();
    }

    /**
     * @param \GearmanJob $job
     */
    public function process(\GearmanJob $job)
    {
        try {
            $this->setJob(unserialize($job->workload()));

            $this->updateLastCollectUpdate();
            /** @var MetricRepository $repository */
            $repository = $this->getContainer('nosql.metric.repository');
            $databaseId = $this->getJob()
                ->getAccount()
                ->getUid();

            $repository->putMetrics(
                $databaseId,
                $this->getJob()->getData()
            );

        } catch (\Throwable $e) {
            $this->errorDispatcher->send($e);
        }

        $repository = null;
        $this->job  = null;

        gc_collect_cycles();
    }
}
