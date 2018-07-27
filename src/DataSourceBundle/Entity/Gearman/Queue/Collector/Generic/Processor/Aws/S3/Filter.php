<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 6/19/17
 * Time: 10:08 AM
 */

namespace DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\S3;

use DataSourceBundle\Entity\Aws\S3\Bucket\Bucket;
use DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\Filter as AwsFilter;

class Filter extends AwsFilter
{

    /**
     * @var Bucket
     */
    protected $bucket;

    /**
     * Filter constructor.
     * @param string $namespace
     * @param array $measurementNames
     * @param Bucket|null $bucket
     */
    public function __construct($namespace, array $measurementNames, Bucket $bucket = null)
    {
        parent::__construct($namespace, $measurementNames);
        (is_null($bucket))?: $this->setBucket($bucket);
    }

    /**
     * @return Bucket
     */
    public function getBucket()
    {
        return $this->bucket;
    }

    /**
     * @param Bucket $bucket
     * @return Filter
     */
    public function setBucket(Bucket $bucket) : Filter
    {
        $this->bucket = $bucket;
        return  $this;
    }

}