<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 5/30/17
 * Time: 3:04 PM
 */

namespace DataSourceBundle\Aws\S3;

use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;
use DataSourceBundle\Aws\ClientAbstract;
use DataSourceBundle\Collection\Aws\S3\Bucket\BucketCollection;
use DataSourceBundle\Entity\Aws\Region\RegionInterface;
use DataSourceBundle\Entity\Aws\S3\Bucket\Bucket;
use DataSourceBundle\Entity\Aws\S3\Bucket\Builder as BucketBuilder;
use DataSourceBundle\Entity\Aws\S3\Bucket\Location;
use DataSourceBundle\Entity\Aws\S3\Bucket\Versioning;
use DataSourceBundle\Entity\Aws\Tag\Builder as TagBuilder;

/**
 * Class S3
 * @package DataSourceBundle\Aws\S3
 */
class S3 extends ClientAbstract
{

    const S3_CLIENT_VERSION = '2006-03-01';

    /**
     * @var S3Client
     */
    protected $s3Cli;

    /**
     * S3 constructor.
     * @param string $key
     * @param string $secret
     * @param RegionInterface $region
     */
    public function __construct(
        $key,
        $secret,
        RegionInterface $region,
        bool $bucketEndpoint = false
    ) {
        parent::__construct($key, $secret, $region);

        $this->s3Cli = new S3Client([
            "region" => $region->getCode(),
            "version" => self::S3_CLIENT_VERSION,
            "credentials" => $this->getCredential(),
            "bucket_endpoint" => $bucketEndpoint
        ]);
    }

    /**
     * @param BucketCollection|null $collection
     * @return BucketCollection
     */
    public function retrieveBuckets(BucketCollection $collection = null): BucketCollection
    {
        $buckets = $this->s3Cli->listBuckets()->search("Buckets[]");
        return (is_null($collection))
            ? BucketBuilder::build($buckets)
            : BucketBuilder::build($buckets, $collection);
    }

    /**
     * @param Bucket $bucket
     * @return S3
     */
    public function updateBucketTag(Bucket $bucket): S3
    {
        try {
            $tags = $this->s3Cli->getBucketTagging([
                "Bucket" => $bucket->getName()
            ])->search("TagSet");
            /**
             * If there is not Tags, an exeption is thrown
             */
        } catch (S3Exception $exception) {
            $tags = [];
        }

        $bucket->setTags(TagBuilder::build($tags));
        return $this;
    }

    /**
     * @param Bucket $bucket
     * @return S3
     */
    public function updateBucketVersioning(Bucket $bucket): S3
    {
        $status = $this->s3Cli->getBucketVersioning([
            "Bucket" => $bucket->getName()
        ])->search("Status");

        $bucket->setVersioning(new Versioning(($status) ?: "Disabled"));
        return $this;
    }

    /**
     * @param Bucket $bucket
     * @return S3
     */
    public function updateBucketLocation(Bucket $bucket): S3
    {
        $location = $this->s3Cli->getBucketLocation([
            "Bucket" => $bucket->getName()
        ])->search("LocationConstraint");

        $bucket->setLocation(new Location($location));
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function checkCredential(): bool
    {
        $this->retrieveBuckets();
        return true;
    }
}
