<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 6/6/17
 * Time: 10:38 AM
 */

namespace DataSourceBundle\Aws\S3\Measurement\S3;

use DataSourceBundle\Aws\MeasurementInterface;
use DataSourceBundle\Collection\Aws\S3\Bucket\BucketCollection;
use DataSourceBundle\Entity\Aws\Region\RegionInterface;
use EssentialsBundle\Entity\DateTime\DateTime;

abstract class MeasurementAbstract extends \DataSourceBundle\Aws\MeasurementAbstract implements MeasurementInterface
{
    /**
     * @var BucketCollection
     */
    protected $buckets;

    /**
     * MeasurementAbstract constructor.
     * @param RegionInterface $region
     * @param DateTime $dateTime
     * @param BucketCollection $buckets
     */
    public function __construct(
        RegionInterface $region,
        DateTime $dateTime,
        BucketCollection $buckets
    ) {
        parent::__construct($region, $dateTime);
        $this->setBuckets($buckets);
    }

    /**
     * @return array
     */
    protected function getNameParts(): array
    {
        return array_merge(
            parent::getNameParts(),
            [ 's3' ]
        );
    }

    /**
     * @return BucketCollection
     */
    public function getBuckets(): BucketCollection
    {
        return $this->buckets;
    }

    /**
     * @param BucketCollection $buckets
     * @return MeasurementAbstract
     */
    public function setBuckets(BucketCollection $buckets): MeasurementAbstract
    {
        $this->buckets = $buckets;
        return $this;
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
