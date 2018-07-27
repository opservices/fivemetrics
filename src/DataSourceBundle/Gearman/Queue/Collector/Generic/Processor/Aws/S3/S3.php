<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 6/12/17
 * Time: 5:20 PM
 */

namespace DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\S3;

use DataSourceBundle\Collection\Aws\S3\Bucket\BucketCollection;
use DataSourceBundle\Entity\Aws\S3\Bucket\Bucket;
use DataSourceBundle\Entity\Aws\S3\Region\RegionProvider;
use DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\S3\Filter;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\S3\Filter\GroupingResolver;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\S3\Job\Builder;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\S3\Job\Job;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\S3\Measurement\Builder as MeasurementBuilder;
use DataSourceBundle\Gearman\Queue\Collector\Generic\ProcessorInterface;
use DataSourceBundle\Gearman\Queue\Collector\Generic\ResultSet;
use EssentialsBundle\KernelLoader;
use GearmanBundle\Job\JobInterface;

/**
 * Class S3
 * @package DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\S3
 */
class S3 implements ProcessorInterface
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
     * @var Builder|null
     */
    protected $jobBuilder;

    /**
     * S3 constructor.
     * @param DataLoader|null $dataLoader
     * @param Builder|null $jobBuilder
     */
    public function __construct(
        DataLoader $dataLoader = null,
        Builder $jobBuilder = null
    ) {
        $this->dataLoader = $dataLoader;

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
     * @param $job
     * @return bool
     */
    public function isValidJob($job): bool
    {
        $class = 'DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\S3\Job\job';
        return ($job instanceof $class);
    }

    /**
     * @param JobInterface $job
     * @return ProcessorInterface
     */
    public function setJob(JobInterface $job): ProcessorInterface
    {
        if (!$this->isValidJob($job)) {
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
        $filters = GroupingResolver::splitMeasurements($this->getJob()->getData());
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
        $data = null;
        $measurement = $filter->getMeasurementNames()[0];

        if ($measurement == "Versioning") {
            $bucket = $this->getJob()->getData()[0];

            if ($bucket) {
                /** @var Filter $bucket */
                $bucket = $bucket->getBucket();

                /** @var Bucket $bucket */
                $this->getDataLoader()->updateBucketLocation($bucket);
                $this->getDataLoader()->updateBucketVersioning($bucket);
                $this->getDataLoader()->updateBucketTag($bucket);
                $data = new BucketCollection();
                $data->add($bucket);
            }
        }

        if (count($data) == 0) {
            return $resultSet;
        }

        $region = ($data->at(0)->getLocation()->getLocationConstraint() == $this->getJob()->getRegion())
            ? $this->getJob()->getRegion()
            : RegionProvider::factory(
                $data->at(0)->getLocation()->getLocationConstraint()
            );

        $measurements = MeasurementBuilder::buildMeasurements(
            $region,
            $this->getJob()->getDateTime(),
            $data,
            $filters
        );

        $resultSet->setMetrics($measurements->getMetrics());
        return $resultSet;
    }

    /**
     * @return Job
     */
    public function getJob(): Job
    {
        return $this->job;
    }

    /**
     * @return Builder|null
     */
    public function getJobBuilder()
    {
        return $this->jobBuilder;
    }
}
