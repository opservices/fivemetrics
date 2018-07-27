<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 6/6/17
 * Time: 9:53 AM
 */

namespace DataSourceBundle\Entity\Aws\S3\Bucket;

use DataSourceBundle\Collection\Aws\S3\Bucket\BucketCollection;
use DataSourceBundle\Entity\Aws\Tag\Builder as TagBuilder;

class Builder
{
    public static function build(
        array $data,
        BucketCollection $buckets = null
    ): BucketCollection {

        if (is_null($buckets)) {
            $buckets = new BucketCollection();
        }

        foreach ($data as $bucket) {
            $buckets->add(
                new Bucket(
                    $bucket["Name"],
                    (empty($bucket["Status"])) ? null : self::buildVersioning($bucket["Status"]),
                    (empty($bucket["LocationConstraint"])) ? null : self::buildLocation($bucket["LocationConstraint"]),
                    (empty($bucket["Tags"])) ? null : TagBuilder::build($bucket["Tags"])
                )
            );
        }

        return $buckets;
    }

    /**
     * @param string $status
     * @return Versioning
     */
    public static function buildVersioning(string $status): Versioning
    {
        return new Versioning($status);
    }

    /**
     * @param string $location
     * @return Location
     */
    public static function buildLocation(string $location): Location
    {
        return new Location($location);
    }
}
