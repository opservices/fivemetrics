<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 03/03/17
 * Time: 09:51
 */

namespace DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EC2;

use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EC2\Filter\GroupingResolver;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EC2\Job\Builder;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EC2\Job\Job;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EC2\Measurement\Builder as MeasurementsBuilder;
use DataSourceBundle\Gearman\Queue\Collector\Generic\ProcessorInterface;
use DataSourceBundle\Gearman\Queue\Collector\Generic\ResultSet;
use EssentialsBundle\KernelLoader;
use GearmanBundle\Job\JobInterface;

/**
 * Class EC2
 * @package DataSourceBundle\Gearman\Queue\Generic\Processor\EC2
 */
class EC2 implements ProcessorInterface
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
     * EC2 constructor.
     * @param DataLoader|null $dataLoader
     * @param Builder|null $jobBuilder
     */
    public function __construct(
        DataLoader $dataLoader = null,
        Builder $jobBuilder = null
    ) {
        (is_null($dataLoader)) ?: $this->dataLoader = $dataLoader;

        $this->jobBuilder = (is_null($jobBuilder))
            ? new Builder()
            : $jobBuilder;
    }

    public function __destruct()
    {
        $this->job = null;
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
        $class = 'DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EC2\Job\job';
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

    public function process(ResultSet $resultSet): ResultSet
    {
        $filters = GroupingResolver::splitMeasurements(
            $this->getJob()->getData()
        );

        if ((count($filters) != 1)
            || ($filters->at(0)->getNamespace() != 'EC2')
        ) {
            $jobs = $this->getJobBuilder()->build($this->getJob(), $filters);
            $resultSet->setJobs($jobs);
            return $resultSet;
        }

        $filter = $filters->at(0);
        $reserves = null;

        if (in_array('Subnets', $filter->getMeasurementNames())) {
            $data = $this->getDataLoader()->retrieveSubnets();
        } elseif (in_array('Reserves', $filter->getMeasurementNames())) {
            $data = $this->getDataLoader()->retrieveInstances(true);
        } else {
            $data = $this->getDataLoader()->retrieveInstances();
        }

        if (count($data) == 0) {
            return $resultSet;
        }

        if (in_array('Reserves', $filter->getMeasurementNames())) {
            $reserves = $this->getDataLoader()->retrieveReservedInstances();
        }

        $measurements = MeasurementsBuilder::buildMeasurements(
            $this->getJob()->getRegion(),
            $this->getJob()->getDateTime(),
            $data,
            $filter,
            $reserves
        );

        $resultSet->setMetrics($measurements->getMetrics());

        $realTimeData = $measurements->getRealTimeData();
        if (! $realTimeData->isEmpty()) {
            $resultSet->setData($realTimeData);
        }

        return $resultSet;
    }
}
