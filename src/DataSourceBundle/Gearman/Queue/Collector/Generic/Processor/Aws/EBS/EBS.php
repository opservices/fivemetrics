<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 8/15/17
 * Time: 10:02 AM
 */

namespace DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EBS;

use DataSourceBundle\Collection\Aws\EBS\Volume\VolumeCollection;
use DataSourceBundle\Collection\Aws\EC2\Instance\InstanceCollection;
use DataSourceBundle\Entity\Aws\EBS\Attachment\Attachment;
use DataSourceBundle\Entity\Aws\EBS\Volume\Volume;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EBS\Filter\GroupingResolver;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EBS\Job\Job;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EBS\Measurement\Builder as MeasurementsBuilder;
use DataSourceBundle\Gearman\Queue\Collector\Generic\ProcessorInterface;
use DataSourceBundle\Gearman\Queue\Collector\Generic\ResultSet;
use EssentialsBundle\KernelLoader;
use GearmanBundle\Job\JobInterface;

/**
 * Class EBS
 * @package DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EBS
 */
class EBS implements ProcessorInterface
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
     * EBS constructor.
     * @param DataLoader|null $dataLoader
     * @param GroupingResolver|null $groupResolver
     */
    public function __construct(
        DataLoader $dataLoader = null,
        GroupingResolver $groupResolver = null
    ) {
        $this->dataLoader = $dataLoader;

        $this->groupResolver = (is_null($groupResolver))
            ? new GroupingResolver()
            : $groupResolver;
    }

    /**
     * @param ResultSet $resultSet
     * @return ResultSet
     */
    public function process(ResultSet $resultSet): ResultSet
    {
        $filters = $this->getGroupResolver()
            ->splitMeasurements(
                $this->getJob()->getData()
            );

        $filter = $filters->at(0);

        $volumes = $this->getDataLoader()->retrieveVolumes();

        if (count($volumes) == 0) {
            return $resultSet;
        }

        $instances = $this->getDataLoader()->retrieveInstances();
        $this->updateVolumeInstance($volumes, $instances);

        $measurements = MeasurementsBuilder::buildMeasurements(
            $this->getJob()->getRegion(),
            $this->getJob()->getDateTime(),
            $volumes,
            $filter
        );

        $resultSet->setMetrics($measurements->getMetrics());

        return $resultSet;
    }

    protected function updateVolumeInstance(
        VolumeCollection $volumes,
        InstanceCollection $instances
    ) {
        /**
         * @var $volume Volume
         */
        foreach ($volumes as $volume) {
            /**
             * @var $attachment Attachment
             */
            foreach ($volume->getAttachments() as $attachment) {
                $state = $attachment->getState();
                if ($state == 'detached') {
                    continue;
                }

                $instance = $instances->find($attachment->getInstanceId());
                $attachment->setInstance($instance);

                break;
            }
        }
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
     * @return Job
     */
    public function getJob(): Job
    {
        return $this->job;
    }

    /**
     * @return DataLoader
     */
    public function getDataLoader(): DataLoader
    {
        if (!is_null($this->dataLoader)) {
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
     * @return GroupingResolver
     */
    public function getGroupResolver(): GroupingResolver
    {
        return $this->groupResolver;
    }
}
