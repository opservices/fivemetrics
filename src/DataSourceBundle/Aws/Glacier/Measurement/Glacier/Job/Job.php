<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 7/26/17
 * Time: 3:27 PM
 */

namespace DataSourceBundle\Aws\Glacier\Measurement\Glacier\Job;

use DataSourceBundle\Collection\Aws\Glacier\Job\JobCollection;
use DataSourceBundle\Entity\Aws\Glacier\Job\Job as GlacierJob;
use DataSourceBundle\Entity\Aws\Region\RegionInterface;
use EssentialsBundle\Collection\Metric\MetricCollection;
use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\Entity\Metric\Builder;

/**
 * Class Job
 * @package DataSourceBundle\Aws\Glacier\Measurement\Glacier\Job
 */
class Job extends MeasurementAbstract
{
    const DATE_FORMAT = 'y-m-d H:00:00';

    /**
     * Job constructor.
     * @param RegionInterface $region
     * @param DateTime $dateTime
     * @param JobCollection $jobs
     */
    public function __construct(
        RegionInterface $region,
        DateTime $dateTime,
        JobCollection $jobs
    ) {
        parent::__construct($region, $dateTime, $jobs);
    }

    /**
     * @return MetricCollection
     */
    public function getMetrics(): MetricCollection
    {
        $buildData = [];
        $jobs = $this->getJobs();

        /**
         * @var $job GlacierJob
         */
        foreach ($jobs as $job) {
            $datetime = new DateTime();
            $key = $job->getJobId()
                . $job->getVault()->getVaultName()
                . $this->getRegion()->getCode()
                . $job->getStatusCode();

            if ($job->getStatusCode() == 'Succeeded') {
                $key = $job->getJobId()
                    . $job->getVault()->getVaultName()
                    . $this->getRegion()->getCode()
                    . $job->getStatusCode()
                    . $job->getCompletionDate()->format(self::DATE_FORMAT);

                if ($job->getCompletionDate()->format(self::DATE_FORMAT) != $datetime->format(self::DATE_FORMAT)) {
                    continue;
                }
            }

            if (! isset($buildData[$key])) {
                $buildData[$key] = [
                    'name' => $this->getName(['vault', 'jobs']),
                    'tags' => $this->getTags($job),
                    'points' => [
                        [
                            'value' => 1,
                            'time' => $this->getMetricsDatetime()
                        ]
                    ]
                ];
            }
        }

        return Builder::build(array_values($buildData));
    }

    /**
     * @param GlacierJob $job
     * @return array
     */
    protected function getTags(GlacierJob $job): array
    {
        $tags = parent::getTags();
        $tags[] = [
            'key' => '::fm::jobStatus',
            'value' => $job->getStatusCode()
        ];

        $tags[] = [
            'key' => '::fm::vaultName',
            'value' => $job->getVault()->getVaultName()
        ];

        $awsTags = array_map(function ($tag) {
            return [
                'key'   => $tag['Key'],
                'value' => $tag['Value'],
            ];
        }, $job->getVault()->getTags()->toArray());

        return array_merge($tags, $awsTags);
    }
}
