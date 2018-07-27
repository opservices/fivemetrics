<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 7/25/17
 * Time: 2:14 PM
 */

namespace DataSourceBundle\Aws\Glacier\Measurement\Glacier\Job;

use DataSourceBundle\Aws\MeasurementInterface;
use DataSourceBundle\Collection\Aws\Glacier\Job\JobCollection;
use DataSourceBundle\Entity\Aws\Region\RegionInterface;
use EssentialsBundle\Entity\DateTime\DateTime;

/**
 * Class MeasurementAbstract
 * @package DataSourceBundle\Aws\Glacier\Measurement\Glacier\Job
 */
abstract class MeasurementAbstract extends \DataSourceBundle\Aws\MeasurementAbstract implements MeasurementInterface
{

    /**
     * @var JobCollection
     */
    protected $jobs;

    /**
     * MeasurementAbstract constructor.
     * @param RegionInterface $region
     * @param DateTime $dateTime
     * @param JobCollection $jobs
     */
    public function __construct(
        RegionInterface $region,
        DateTime $dateTime,
        JobCollection $jobs
    ) {
        parent::__construct($region, $dateTime);
        $this->setJobs($jobs);
    }

    /**
     * @return JobCollection
     */
    public function getJobs(): JobCollection
    {
        return $this->jobs;
    }

    /**
     * @param JobCollection $jobs
     * @return MeasurementAbstract
     */
    public function setJobs(JobCollection $jobs): MeasurementAbstract
    {
        $this->jobs = $jobs;
        return $this;
    }

    /**
     * @return array
     */
    protected function getNameParts(): array
    {
        return array_merge(
            parent::getNameParts(),
            [ 'glacier' ]
        );
    }

    /**
     * @return array
     */
    protected function getTags(): array
    {
        $tags = parent::getTags();

        $tags[] = [
            'key' => '::fm::region',
            'value' => $this->getRegion()->getCode()
        ];

        return $tags;
    }
}
