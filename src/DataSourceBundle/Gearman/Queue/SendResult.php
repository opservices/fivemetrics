<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 20/05/17
 * Time: 22:37
 */

namespace DataSourceBundle\Gearman\Queue;

use DatabaseBundle\Gearman\Queue\CollectResult\Job;
use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Entity\Builder\EntityBuilderProvider;
use EssentialsBundle\Entity\Metric\Builder;
use EssentialsBundle\KernelLoader;
use GearmanBundle\Collection\Worker\QueueCollection;
use GearmanBundle\TaskManager\TaskManager;
use GearmanBundle\Worker\Queue;
use GuzzleHttp\Client;
use InfluxDB\Database;

/**
 * Class SendResult
 * @package DataSourceBundle\Gearman\Queue\NoSql
 */
class SendResult extends QueueAbstract
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var TaskManager
     */
    protected $tm;

    public function __construct(
        $jobServers = null,
        $worker = null,
        $configuration = null,
        $errorDispatcher = null,
        TaskManager $taskManager = null
    ) {
        parent::__construct($jobServers, $worker, $configuration, $errorDispatcher);
        $this->tm = (is_null($taskManager)) ? new TaskManager() : $taskManager;
    }

    protected function getQueues(): QueueCollection
    {
        $queues = new QueueCollection();
        $queues->add(new Queue("active-collect-result", "process"));

        return $queues;
    }

    /**
     * @param $job
     * @return bool
     */
    public function getJobType(): string
    {
        return Job::class;
    }

    protected function buildMetrics($data)
    {
        return Builder::build(json_decode($data, true));
    }

    /**
     * @param string $databaseId
     * @return Database
     */
    protected function getNoSqlDatabase(string $databaseId): Database
    {
        return KernelLoader::load()
            ->getContainer()
            ->get('algatux_influx_db.connection.app.http')
            ->getClient()
            ->selectDB($databaseId);
    }

    /**
     * @param $job
     */
    public function prepare(\GearmanJob $job)
    {
        $jobData = unserialize($job->workload());

        if (! $this->isValidJob($jobData)) {
            throw new \InvalidArgumentException(
                __CLASS__ .": An invalid job has been provided."
            );
        }

        $this->setJob($jobData);
    }

    /**
     * @return Client
     */
    public function getHttpClient(): Client
    {
        if (is_null($this->client)) {
            $this->client = new Client([
                'base_uri' => 'http://localhost/',
                'defaults' => [
                    'exceptions' => false
                ]
            ]);
        }

        return $this->client;
    }

    public function process(\GearmanJob $job)
    {

        $accountBuilder = EntityBuilderProvider::factory(Account::class);
        $account = $accountBuilder->factory([
            'uid' => 'system',
            'email' => 'system@fivemetrics.io'
        ]);

        try {
            $this->prepare($job);
            $account = $this->getJob()->getAccount();

            $this->tm->runBackground(
                'active-collect-writer',
                serialize(new Job(
                    $account,
                    $this->getJob()->getCollectId(),
                    $this->getJob()->getDateTime(),
                    $this->getJob()->getData()
                ))
            );
        } catch (\Throwable $e) {
            $this->getResultSetInstance($account)->setError($e->getMessage());
        } finally {
            $job->sendComplete(serialize($this->getResultSetInstance($account)));

            (is_null($this->getResultSetInstance($account)->getError()))
                ?: $this->errorDispatcher->send(
                    $this->getResultSetInstance($account)->getError(),
                    $job
                );
        }

        $this->resultSet = null;
        $this->job = null;

        gc_collect_cycles();
    }
}
