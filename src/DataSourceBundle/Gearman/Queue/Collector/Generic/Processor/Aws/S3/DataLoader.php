<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 6/12/17
 * Time: 5:25 PM
 */

namespace DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\S3;

use DataSourceBundle\Aws\S3\S3;
use DataSourceBundle\Collection\Aws\S3\Bucket\BucketCollection;
use DataSourceBundle\Entity\Aws\Region\RegionProvider;
use DataSourceBundle\Entity\Aws\S3\Bucket\Bucket;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\DataLoaderAbstract;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\S3\Job\Job;
use Doctrine\Common\Cache\CacheProvider;

/**
 * Class DataLoader
 * @package DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\S3
 */
class DataLoader extends DataLoaderAbstract
{
    /**
     * @var S3
     */
    protected $client;

    /**
     * DataLoader constructor.
     * @param Job $job
     * @param CacheProvider|null $cacheProvider
     * @param S3|null $s3Client
     */
    public function __construct(Job $job, CacheProvider $cacheProvider = null, S3 $s3Client = null)
    {
        parent::__construct($job, $cacheProvider);
        $this->client = $s3Client;
    }

    public function __destruct()
    {
        $this->client = null;
    }

    /**
     * @return S3
     */
    protected function getS3Client()
    {
        if (is_null($this->client)) {
            $this->client = new S3(
                $this->getJob()->getKey(),
                $this->getJob()->getSecret(),
                $this->getJob()->getRegion(),
                true
            );
        }
        return $this->client;
    }

    /**
     * @return BucketCollection
     */
    public function retrieveBuckets(): BucketCollection
    {
        $key = __CLASS__ . __METHOD__;
        $data = $this->cacheFetch($key);
        if ($data) {
            return $data;
        }

        $data = $this->getS3Client()->retrieveBuckets();
        $this->cacheWrite($key, $data);
        return $data;
    }

    /**
     * @param Bucket $bucket
     */
    public function updateBucketLocation(Bucket $bucket)
    {
        $this->getS3Client()->updateBucketLocation($bucket);
    }

    /**
     * @param Bucket $bucket
     */
    public function updateBucketTag(Bucket $bucket)
    {
        $s3Client = new S3(
            $this->getJob()->getKey(),
            $this->getJob()->getSecret(),
            RegionProvider::factory($bucket->getLocation()->getLocationConstraint())
        );
        $s3Client->updateBucketTag($bucket);
        unset($s3Client);
    }

    /**
     * @param Bucket $bucket
     */
    public function updateBucketVersioning(Bucket $bucket)
    {
        $s3Client = new S3(
            $this->getJob()->getKey(),
            $this->getJob()->getSecret(),
            RegionProvider::factory($bucket->getLocation()->getLocationConstraint())
        );
        $s3Client->updateBucketVersioning($bucket);
        unset($s3Client);
    }
}
