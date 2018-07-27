<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 7/27/17
 * Time: 11:57 AM
 */

namespace DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier;

use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier\Filter\GroupingResolver;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier\Job\Builder;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier\Job\Job;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier\Measurement\Builder as MeasurementsBuilder;
use DataSourceBundle\Gearman\Queue\Collector\Generic\ProcessorInterface;
use DataSourceBundle\Gearman\Queue\Collector\Generic\ResultSet;
use EssentialsBundle\KernelLoader;
use GearmanBundle\Job\JobInterface;

/**
 * Class Glacier
 * @package DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier
 */
class Glacier implements ProcessorInterface
{
    /**
     * @var $job Job
     */
    protected $job;

    /**
     * @var DataLoader|null
     */
    protected $dataLoader;

    /**
     * @var GroupingResolver|null
     */
    protected $groupResolver;

    /**
     * @var Builder
     */
    protected $jobBuilder;

    /**
     * Glacier constructor.
     * @param DataLoader|null $dataLoader
     * @param GroupingResolver|null $groupResolver
     * @param Builder|null $jobBuilder
     */
    public function __construct(
        DataLoader $dataLoader = null,
        GroupingResolver $groupResolver = null,
        Builder $jobBuilder = null
    ) {
        $this->dataLoader = $dataLoader;

        $this->groupResolver = (is_null($groupResolver))
            ? new GroupingResolver()
            : $groupResolver;

        $this->jobBuilder = (is_null($jobBuilder))
            ? new Builder()
            : $jobBuilder;
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

        if ((count($jobs) != 1)) {
            $resultSet->setJobs($jobs);
            return $resultSet;
        }

        $filter = $filters->at(0);

        if ($filter->getMeasurementNames()[0] == "VaultSize"
            || $filter->getMeasurementNames()[0] == "VaultArchive"
        ) {
            $data = $this->getDataLoader()->retrieveVaults();
        }

        if ($filter->getMeasurementNames()[0] == "Job") {
            $vault = $this->getJob()->getData()[0]->getVault();
            $data = $this->getDataLoader()->getJobsByVault($vault);
        }

        if (count($data) == 0) {
            return $resultSet;
        }

        $measurements = MeasurementsBuilder::buildMeasurements(
            $this->getJob()->getRegion(),
            $this->getJob()->getDateTime(),
            $data,
            $filter
        );

        $resultSet->setMetrics($measurements->getMetrics());
        return $resultSet;
    }

    /**
     * @return GroupingResolver|null
     */
    public function getGroupResolver()
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
     * @return DataLoader|null
     */
    public function getDataLoader()
    {
        if (! is_null($this->dataLoader)) {
            return $this->dataLoader;
        }

        $cacheFactory = KernelLoader::load()
            ->getContainer()
            ->get('cache.factory');

        $cacheProvider = $cacheFactory->factory(
            $this->getJob()->getAccount(),
            'local_cache'
        );

        $this->dataLoader = new DataLoader(
            $this->getJob(),
            $cacheProvider
        );

        return $this->dataLoader;
    }

    /**
     * @param $job
     * @return bool
     */
    public function isValidJob($job): bool
    {
        $class = Job::class;
        return ($job instanceof $class);
    }

    /**
     * @return Job
     */
    public function getJob()
    {
        return $this->job;
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
}
