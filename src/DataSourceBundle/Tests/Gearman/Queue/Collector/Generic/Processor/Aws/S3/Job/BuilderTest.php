<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 7/11/17
 * Time: 4:19 PM
 */

namespace DataSourceBundle\Tests\Gearman\Queue\Collector\Generic\Processor\Aws\S3\Job;

use DataSourceBundle\Collection\Aws\S3\Bucket\BucketCollection;
use DataSourceBundle\Collection\Gearman\Job\JobCollection;
use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\S3\FilterCollection;
use DataSourceBundle\Entity\Aws\S3\Bucket\Bucket;
use DataSourceBundle\Entity\Aws\S3\Bucket\Location;
use DataSourceBundle\Entity\Aws\S3\Region\RegionProvider;
use DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\S3\Filter;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\S3\DataLoader;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\S3\Job\Builder;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\S3\Job\Job;
use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Entity\DateTime\DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Class BuilderTest
 * @package DataSourceBundle\Tests\Gearman\Queue\Collector\Generic\Processor\Aws\S3\Job
 */
class BuilderTest extends TestCase
{

    /**
     * @var Builder
     */
    protected $builder;

    public function setUp()
    {
        $this->builder = new Builder();
    }

    /**
     * @test
     */
    public function buildInitialJobTest()
    {
        $job = $this->getS3JobWithOutDataTest();
        $filters = new FilterCollection([
            new Filter(
                'S3',
                ['Versioning']
            )
        ]);

        $dataLoader = $this->getMockBuilder(
            DataLoader::class
        )->disableOriginalConstructor()
            ->getMock();

        $expectedResult = $this->getInitialJobs();

        $dataLoader->expects($this->once())
            ->method('retrieveBuckets')
            ->willReturn($this->getBuckets());

        $jobs = $this->builder->build($dataLoader, $job, $filters);

        $this->assertEquals(
            count($expectedResult),
            count($jobs)
        );
    }

    /**
     * @test
     */
    public function buildJobTest()
    {
        $job = $this->getS3JobWithDataTest();

        $filters = new FilterCollection([
            new Filter(
                'S3',
                ['Versioning']
            )
        ]);

        $dataLoader = $this->getMockBuilder(
            DataLoader::class
        )->disableOriginalConstructor()
            ->getMock();

        $expectedResult = $this->getInitialJobs()->at(0);
        $jobs = $this->builder->build($dataLoader, $job, $filters);
        $this->assertEquals(
            count($expectedResult),
            count($jobs)
        );
    }

    public function getS3JobWithOutDataTest()
    {
        $account = new Account();
        $account->setUid('unitTest');

        return new Job(
            $account,
            DateTime::createFromFormat(
                'Y-m-d H:i:s',
                '2017-08-07 14:10:00'
            ),
            RegionProvider::factory('us-east-1'),
            'job',
            time() . rand(1, 100)
        );
    }

    public function getS3JobWithDataTest()
    {
        $job = $this->getS3JobWithOutDataTest();
        $filter = new Filter('S3', ['Versioning']);
        $bucket = new Bucket('Bucket1');
        $bucket->setLocation(new Location('us-east-1'));
        $filter->setBucket($bucket);
        $filterCollection = new FilterCollection();
        $filterCollection->add($filter);
        $job->setData($filterCollection);
        return $job;
    }

    public function getInitialJobs()
    {
        $account = new Account();
        $account->setUid('unitTest');

        $baseJob = new Job(
            $account,
            DateTime::createFromFormat(
                'Y-m-d H:i:s',
                '2017-08-07 14:10:00'
            ),
            RegionProvider::factory('us-east-1'),
            'job',
            time() . rand(1, 100)
        );

        $filter = new Filter('S3', ['Versioning']);
        $jobs = new JobCollection();
        $newFilters = new FilterCollection();

        for ($i = 0; $i < 2; $i++) {
            $job = clone($baseJob);
            $filter->setBucket(new Bucket('Bucket' . $i));
            $newFilters->add(clone($filter));
            $job->setData(clone($newFilters));
            $jobs->add($job);
            $newFilters->clear();
        }
        return $jobs;
    }

    public function getBuckets()
    {
        $bucketCollection = new BucketCollection();
        for ($i = 0; $i < 2; $i++) {
            $bucketCollection->add(new Bucket('Bucket' . $i));
        }
        return $bucketCollection;
    }
}
