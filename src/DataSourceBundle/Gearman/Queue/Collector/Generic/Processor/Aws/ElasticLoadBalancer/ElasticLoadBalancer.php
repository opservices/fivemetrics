<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 07/03/17
 * Time: 18:54
 */

namespace DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer;

use DataSourceBundle\Aws\EC2\Measurement\Measurements;
use DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer\Filter;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer\Filter\GroupingResolver;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer\Job\Builder;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer\Job\Job;
use DataSourceBundle\Gearman\Queue\Collector\Generic\ProcessorInterface;
use DataSourceBundle\Gearman\Queue\Collector\Generic\ResultSet;
use EssentialsBundle\KernelLoader;
use GearmanBundle\Job\JobInterface;

/**
 * Class ElasticLoadBalancer
 * @package DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer
 */
class ElasticLoadBalancer implements ProcessorInterface
{
    /**
     * @var Job
     */
    protected $job;

    /**
     * @var DataLoader
     */
    protected $dataLoader;

    /**
     * @var Builder
     */
    protected $jobBuilder;

    /**
     * @var GroupingResolver
     */
    protected $groupResolver;

    /**
     * ElasticLoadBalancer constructor.
     * @param DataLoader|null $dataLoader
     * @param GroupingResolver|null $groupResolver
     * @param Builder|null $jobBuilder
     */
    public function __construct(
        DataLoader $dataLoader = null,
        GroupingResolver $groupResolver = null,
        Builder $jobBuilder = null
    ) {
        (is_null($dataLoader)) ?: $this->dataLoader = $dataLoader;

        $this->groupResolver = (is_null($groupResolver))
            ? new GroupingResolver()
            : $groupResolver;

        $this->jobBuilder = (is_null($jobBuilder))
            ? new Builder()
            : $jobBuilder;
    }

    /**
     * @return DataLoader
     */
    public function getDataLoader(): DataLoader
    {
        if (is_null($this->dataLoader)) {
            $cacheFactory = KernelLoader::load()
                ->getContainer()
                ->get('cache.factory');

            $this->dataLoader = new DataLoader(
                $this->getJob(),
                $cacheFactory->factory(
                    $this->getJob()->getAccount(),
                    'local_cache'
                )
            );
        }

        return $this->dataLoader;
    }

    /**
     * @return GroupingResolver
     */
    public function getGroupResolver(): GroupingResolver
    {
        return $this->groupResolver;
    }

    /**
     * @return Builder
     */
    public function getJobBuilder(): Builder
    {
        return $this->jobBuilder;
    }

    /**
     * @return Job
     */
    public function getJob(): Job
    {
        return $this->job;
    }

    /**
     * @param $job
     * @return bool
     */
    public function isValidJob($job): bool
    {
        $class = 'DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer\Job\job';
        return ($job instanceof $class);
    }

    /**
     * @param JobInterface $job
     * @return ProcessorInterface
     */
    public function setJob(JobInterface $job): ProcessorInterface
    {
        if (! $this->isValidJob($job)) {
            throw new \InvalidArgumentException(
                "An invalid job has been provided."
            );
        }

        $this->job = $job;
        return $this;
    }

    /**
     * @param ResultSet $resultSet
     * @return ResultSet
     */
    public function process(ResultSet $resultSet): ResultSet
    {
        $filters = $this->getGroupResolver()->splitMeasurements(
            $this->getJob()->getData()
        );

        $jobs = $this->getJobBuilder()->build(
            $this->getDataLoader(),
            $this->getJob(),
            $filters
        );

        if (count($jobs) != 1) {
            if (count($jobs) > 0) {
                // Request to create a local cache
                // This block ("if") should run only on the first job of a set.
                $this->getDataLoader()->retrieveInstances();
            }

            $resultSet->setJobs($jobs);
            return $resultSet;
        }

        $measurements = new Measurements();

        $job     = $jobs->at(0);
        /** @var Filter $filter */
        $filter  = $job->getData()->at(0);
        $elbs    = $filter->getElbs();
        $elbName = $elbs->at(0)->getLoadBalancerName();
        $instanceHealth = $this->getDataLoader()
            ->retrieveElasticLoadBalancerInstanceHealth(
                $elbName
            );

        $elbs->at(0)->setInstanceHealth(
            $instanceHealth
        );

        $measurementNames = $filter->getMeasurementNames();
        $instances = $this->getDataLoader()->retrieveInstances();

        foreach ($measurementNames as $measurement) {
            $class = sprintf(
                "DataSourceBundle\\Aws\\EC2\\Measurement\\%s\\%s",
                $filter->getNamespace(),
                $measurement
            );

            $measurements->addMeasurement(new $class(
                $this->getJob()->getRegion(),
                $this->getJob()->getDateTime(),
                $elbs,
                $instances
            ));
        }

        $resultSet->setMetrics($measurements->getMetrics());

        return $resultSet;
    }
}
