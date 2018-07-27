<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 6/6/17
 * Time: 10:13 AM
 */

namespace DataSourceBundle\Collection\Aws\S3\Bucket;

use EssentialsBundle\Collection\TypedCollectionAbstract;

/**
 * Class BucketCollection
 * @package DataSourceBundle\Collection\Aws\S3\Bucket
 */
class BucketCollection extends TypedCollectionAbstract
{
    /**
     * @return string
     */
    public function getClass(): string
    {
        return 'DataSourceBundle\Entity\Aws\S3\Bucket\Bucket';
    }

    /**
     * @param null $added
     * @param null $removed
     * @return mixed
     */
    protected function onChanged($added = null, $removed = null)
    {
    }
}
