<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 7/11/17
 * Time: 10:54 AM
 */

namespace DataSourceBundle\Tests\Entity\Aws\S3\Bucket;

use DataSourceBundle\Entity\Aws\S3\Bucket\Builder;
use PHPUnit\Framework\TestCase;

/**
 * Class BuilderTest
 * @package DataSourceBundle\Tests\Entity\Aws\S3\Bucket
 */
class BuilderTest extends TestCase
{

    /**
     * @test
     * @dataProvider getDataValidToBucketProvider
     */
    public function bucket($data)
    {
        $bucket = Builder::build([$data]);
        $this->assertEquals('newBucket', $bucket->current()->getName());
        $this->assertEquals('Enabled', $bucket->current()->getVersioning()->getStatus());
        $this->assertEquals('us-east-2', $bucket->current()->getLocation()->getLocationConstraint());
        $this->assertGreaterThan(0, count($bucket->current()->getTags()));
    }

    /**
     * @test
     * @dataProvider getDataInvalidToBucketProvider
     * @expectedException \RuntimeException
     */
    public function bucketGetVersioningException($data)
    {
        $bucket = Builder::build([$data]);
        $bucket->current()->getVersioning();
        $this->expectException('RuntimeException');
    }

    /**
     * @test
     * @dataProvider getDataInvalidToBucketProvider
     * @expectedException \RuntimeException
     */
    public function bucketGetLocationException($data)
    {
        $bucket = Builder::build([$data]);
        $bucket->current()->getLocation();
        $this->expectException('RuntimeException');
    }

    public function getDataValidToBucketProvider()
    {

        $location = 'us-east-2';
        $versioning = 'Enabled';
        $tags = [
            [
                'Key' => 'foo',
                'Value' => 'bar'
            ]
        ];
        $data = [
          [
              'Name' => 'newBucket',
              'Status' => $versioning,
              'LocationConstraint' => $location,
              'Tags' => $tags
          ]
        ];
        return [$data];
    }

    public function getDataInvalidToBucketProvider()
    {
        $data = [
            [
                'Name' => 'newBucket'
            ]
        ];
        return [$data];
    }
}
