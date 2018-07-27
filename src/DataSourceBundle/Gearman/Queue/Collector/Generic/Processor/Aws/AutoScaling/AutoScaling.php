<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 09/03/17
 * Time: 19:44
 */

namespace DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScaling;

use DataSourceBundle\Entity\Gearman\Metadata\Metadata;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScaling\Filter\GroupingResolver;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScaling\Job\Builder;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScaling\Job\Job;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScaling\Measurement\Builder as MeasurementsBuilder;
use DataSourceBundle\Gearman\Queue\Collector\Generic\ProcessorInterface;
use DataSourceBundle\Gearman\Queue\Collector\Generic\ResultSet;
use EssentialsBundle\KernelLoader;
use GearmanBundle\Job\JobInterface;

/**
 * Class AutoScaling
 * @package DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScaling
 */
class AutoScaling implements ProcessorInterface
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
     * @var GroupingResolver
     */
    protected $groupResolver;

    /**
     * @var Builder
     */
    protected $jobBuilder;

    /**
     * AutoScaling constructor.
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

    public function __destruct()
    {
        $this->job = null;
        $this->groupResolver = null;
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
     * @return DataLoader
     */
    public function getDataLoader(): DataLoader
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
        return (is_a(
            $job,
            'DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScaling\Job\Job'
        ));
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

        if ((count($jobs) != 1)) {
            $resultSet->setJobs($jobs);
            return $resultSet;
        }

        $filter = $filters->at(0);

        if ((count($filter->getMeasurementNames()) == 1)
            && ($filter->getMeasurementNames()[0] == 'Activities')
        ) {
            $groupName  = $filter->getAutoScalingGroup()
                ->getAutoScalingGroupName();
            $activities = $this->getDataLoader()
                ->retrieveAutoScalingActivities($groupName);

            $resultSet->getData()->add(new Metadata($activities));
            return $resultSet;
        }

        $data = $this->getDataLoader()->retrieveAutoScalingGroups();

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
}
