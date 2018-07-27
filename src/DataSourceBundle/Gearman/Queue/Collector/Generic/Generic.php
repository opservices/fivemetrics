<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 24/02/17
 * Time: 15:52
 */

namespace DataSourceBundle\Gearman\Queue\Collector\Generic;

use Aws\Api\Parser\Exception\ParserException;
use Aws\Exception\AwsException;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Job\JobInterface;
use DataSourceBundle\Gearman\Queue\QueueAbstract;
use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\ConfigurationCollection;
use EssentialsBundle\Entity\Metric\Metric;
use EssentialsBundle\Entity\Tag\Tag;
use GearmanBundle\Collection\Worker\QueueCollection;
use GearmanBundle\Worker\Queue;
use GearmanBundle\Worker\WorkerInterface;
use EssentialsBundle\Profiler\Profiler;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Generic
 * @package DataSourceBundle\Gearman\Queue\Collector\Generic
 */
class Generic extends QueueAbstract
{
    const DEFAULT_PROCESSOR_RETRIES = 2;

    const PROCESSORS_NAMESPACE = 'DataSourceBundle\Gearman\Queue\Collector\Generic\Processor';

    /**
     * Generic constructor.
     */
    public function __construct(
        $jobServers = null,
        $worker = null,
        $configuration = null
    ) {
        parent::__construct($jobServers, $worker, $configuration);
    }

    /**
     * @return ConfigurationCollection
     */
    public function getConfiguration(): ConfigurationCollection
    {
        return $this->configuration;
    }

    /**
     * @param $configuration
     * @return WorkerInterface
     */
    public function setConfiguration($configuration): WorkerInterface
    {
        $this->configuration = Configuration::build($configuration['processors']);
        return $this;
    }

    protected function getQueues(): QueueCollection
    {
        return new QueueCollection([
            new Queue("collect-processor", "process")
        ]);
    }

    /**
     * @return string
     */
    public function getJobType(): string
    {
        return JobInterface::class;
    }

    /**
     * @param $job
     * @return ProcessorInterface
     */
    public function prepare($job): ProcessorInterface
    {
        if (! $this->isValidJob($job)) {
            throw new \InvalidArgumentException(
                __CLASS__ . ": An invalid job has been provided."
            );
        }
        /** @var JobInterface $job */
        $processor = sprintf(
            '%s\%s',
            self::PROCESSORS_NAMESPACE,
            $job->getProcessor()
        );

        return new $processor();
    }

    /**
     * @param string $processorName
     * @return int
     */
    protected function getMaxRetries(string $processorName): int
    {
        $conf = $this->getConfiguration()
            ->find($processorName);

        return (is_null($conf))
            ? self::DEFAULT_PROCESSOR_RETRIES
            : $conf->getMaxRetries();
    }

    /**
     * @param Profiler $profiler
     * @param ResultSet $resultSet
     * @return ResultSet
     */
    protected function updateResultSetJobs(
        Profiler $profiler,
        ResultSet $resultSet
    ): ResultSet {
        $jobs = $resultSet->getJobs();
        $metrics = $resultSet->getMetrics();
        $metricNames = [];
        $ds = $profiler->getTags()->find('dataSource');
        $ds = (is_null($ds)) ? null : $ds->getValue();

        foreach ($metrics as $metric) {
            /** @var Metric $metric */
            $metricNames[] = ($ds)
                ? str_replace($ds . '.', '', $metric->getName())
                : $metric->getName();
        }

        $metricNames = array_unique($metricNames);

        $metricsTag = $profiler->getTags()->find('metrics');
        if (is_null($metricsTag)) {
            $metricsTag = new Tag('metrics');
            $profiler->getTags()->add($metricsTag);
        }

        $metricsTag->setValue(implode(',', $metricNames));

        foreach ($jobs as $job) {
            /** @var JobInterface $job */
            $job->setProfiler($profiler);
        }

        return $resultSet;
    }

    /**
     * @param \GearmanJob $job
     */
    public function process(\GearmanJob $job)
    {
        $retries = 0;
        $this->setJob(unserialize($job->workload()));

        $maxRetries = $this->getMaxRetries($this->getJob()->getProcessor());
        $processor = $this->prepare($this->getJob());
        $processor->setJob($this->getJob());
        $account = $this->getJob()->getAccount();

        while ($retries < $maxRetries) {
            try {
                $processor->process(
                    $this->getResultSetInstance($account)
                );
            } catch (AwsException $e) {
                if ($e->getStatusCode() == Response::HTTP_UNAUTHORIZED) {
                    $retries = $maxRetries;
                }

                if ($retries++ < $maxRetries) {
                    sleep($retries);
                    continue;
                }

                $this->errorDispatcher->send($e, $this->getJob());
                $this->getResultSetInstance($account)
                    ->setError($e->getAwsErrorMessage());
            } catch (ParserException $e) {
                if ($retries++ < $maxRetries) {
                    $this->errorDispatcher->send($e, [ 'retries' => $retries, 'job' => $this->getJob() ]);

                    sleep($retries);
                    continue;
                }

                $this->getResultSetInstance($account)
                    ->setError($e->getMessage());
            } catch (\Throwable $e) {
                $this->errorDispatcher->send($e, $this->getJob());
                $this->getResultSetInstance($account)
                    ->setError($e->getMessage());
            } finally {
                $profiler = $this->getJob()->getProfiler();
                $resultSet = $this->getResultSetInstance($account);
                if ($profiler) {
                    $resultSet = $this->updateResultSetJobs($profiler, $resultSet);
                }

                $job->sendComplete(serialize($resultSet));

                (is_null($resultSet->getError()))
                    ?: $this->errorDispatcher->send(
                        $resultSet->getError(),
                        $this->getJob()
                    );

                $retries = $maxRetries;
            }
        }

        $this->resultSet = null;
        $this->job       = null;
        $genericJob      = null;
        $processor       = null;
        $result          = null;
        $job             = null;
        $profiler       = null;

        gc_collect_cycles();
    }
}
