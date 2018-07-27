<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 6/7/17
 * Time: 11:08 AM
 */

namespace DataSourceBundle\Aws\S3\Measurement\S3;

use DataSourceBundle\Collection\Aws\S3\Bucket\BucketCollection;
use DataSourceBundle\Entity\Aws\Region\RegionInterface;
use DataSourceBundle\Entity\Aws\S3\Bucket\Bucket;
use EssentialsBundle\Collection\Metric\MetricCollection;
use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\Entity\Metric\Builder;

/**
 * Class Versioning
 * @package DataSourceBundle\Aws\S3\Measurement\S3
 */
class Versioning extends MeasurementAbstract
{
    /**
     * @var BucketCollection
     */
    protected $buckets;

    /**
     * @var DateTime
     */
    protected $dateTime;

    /**
     * Versioning constructor.
     * @param RegionInterface $region
     * @param DateTime|null $dateTime
     * @param BucketCollection $buckets
     */
    public function __construct(
        RegionInterface $region,
        DateTime $dateTime = null,
        BucketCollection $buckets
    ) {
        parent::__construct($region, $dateTime, $buckets);
    }

    /**
     * @return MetricCollection
     */
    public function getMetrics(): MetricCollection
    {
        $buildData = [];
        $buckets = $this->getBuckets();
        /**
         * @var $bucket Bucket
         */
        foreach ($buckets as $bucket) {
            $key = $bucket->getName()
                . $bucket->getVersioning()->getStatus()
                . $bucket->getLocation()->getLocationConstraint();
            if (!isset($buildData[$key])) {
                $buildData[$key] = [
                    'name' => $this->getName(['bucket', 'versioning']),
                    'tags' => $this->getTags($bucket),
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
     * @param Bucket $bucket
     * @return array
     */
    protected function getTags(Bucket $bucket): array
    {
        $tags = parent::getTags();

        $tags[] = [
            'key' => '::fm::versioning',
            'value' => $bucket->getVersioning()->getStatus()
        ];

        $tags[] = [
            'key' => '::fm::bucketName',
            'value' => $bucket->getName()
        ];

        $awsTags = array_map(function ($tag) {
            return [
                'key'   => $tag['Key'],
                'value' => $tag['Value'],
            ];
        }, $bucket->getTags()->toArray());

        return array_merge($tags, $awsTags);
    }
/*

    public function getDateTimeToMetrics()
    {
        return $this->datetime;
    }


    protected function setDateTimeToMetrics(DateTime $dateTime): Versioning
    {
        $this->datetime = $dateTime;
        return $this;
    }*/
}
