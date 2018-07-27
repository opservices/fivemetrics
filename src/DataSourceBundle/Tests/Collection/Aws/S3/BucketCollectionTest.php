<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 7/11/17
 * Time: 9:47 AM
 */

namespace DataSourceBundle\Tests\Collection\Aws\S3;

use DataSourceBundle\Collection\Aws\S3\Bucket\BucketCollection;
use DataSourceBundle\Entity\Aws\S3\Bucket\Bucket;
use PHPUnit\Framework\TestCase;

/**
 * Class BucketCollectionTest
 * @package DataSourceBundle\Tests\Collection\Aws\S3
 */
class BucketCollectionTest extends TestCase
{
    /**
     * @var BucketCollection
     */
    protected $bucketCollection;

    public function setUp()
    {
        $this->bucketCollection = new BucketCollection();
    }

    /**
     * @test
     */
    public function addBucket()
    {
        $this->bucketCollection->add(new Bucket('teste'));

        $this->assertEquals(
            1,
            count($this->bucketCollection)
        );
    }
}
