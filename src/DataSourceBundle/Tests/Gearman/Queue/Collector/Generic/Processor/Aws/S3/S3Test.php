<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 7/13/17
 * Time: 11:17 AM
 */

namespace DataSourceBundle\Tests\Gearman\Queue\Collector\Generic\Processor\Aws\S3;

use DataSourceBundle\Collection\Aws\S3\Bucket\BucketCollection;
use DataSourceBundle\Collection\Aws\Tag\TagCollection;
use DataSourceBundle\Collection\Gearman\Job\JobCollection;
use DataSourceBundle\Collection\Gearman\Metadata\MetadataCollection;
use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\S3\FilterCollection;
use DataSourceBundle\Entity\Aws\S3\Bucket\Bucket;
use DataSourceBundle\Entity\Aws\S3\Bucket\Location;
use DataSourceBundle\Entity\Aws\S3\Bucket\Versioning;
use DataSourceBundle\Entity\Aws\S3\Region\RegionProvider;
use DataSourceBundle\Entity\Aws\Tag\Tag;
use DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\S3\Filter;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\S3\DataLoader;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\S3\Job\Builder;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\S3\Job\Job;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\S3\S3;
use DataSourceBundle\Gearman\Queue\Collector\Generic\ResultSet;
use Doctrine\Common\Cache\CacheProvider;
use EssentialsBundle\Collection\Metric\MetricCollection;
use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Entity\DateTime\DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Class S3Test
 * @package DataSourceBundle\Tests\Gearman\Queue\Collector\Generic\Processor\Aws\S3
 */
class S3Test extends TestCase
{

    /**
     * @var S3
     */
    private $s3;

    private static $account;

    public static function setUpBeforeClass()
    {
        self::$account = new Account();
        self::$account->setUid('test');
    }

    public function setUp()
    {
        $this->s3 = new S3();
        $this->s3->setJob(new Job(
            self::$account,
            DateTime::createFromFormat(
                'Y-m-d H:i:s',
                '2017-08-07 14:10:00'
            ),
            RegionProvider::factory('us-east-1'),
            'key',
            'secret'
        ));
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function setInvalidJob()
    {
        $this->s3->setJob(
            new \DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer\Job\Job(
                self::$account,
                DateTime::createFromFormat(
                    'Y-m-d H:i:s',
                    '2017-08-07 14:10:00'
                ),
                RegionProvider::factory('us-east-1'),
                'key',
                'secret'
            )
        );
    }

    /**
     * @test
     */
    public function getJob()
    {
        $job = $this->s3->getJob();
        $this->assertInstanceOf(Job::class, $job);
    }

    /**
     * @test
     */
    public function getDataLoader()
    {
        $cache = $this->getCacheMock();
        $job = $this->getJobInstance();
        $dataLoader = $this->getDataLoaderInstance($job, $cache);

        $s3 = new S3($dataLoader);

        $loader = $s3->getDataLoader();
        $this->assertInstanceOf(DataLoader::class, $loader);
        $this->assertSame($dataLoader, $loader);

        $newS3 = new S3();
        $newS3->setJob($job);
        $this->assertInstanceOf(DataLoader::class, $newS3->getDataLoader());

        return $dataLoader;
    }

    private function getCacheMock()
    {
        return $this->getMockBuilder(CacheProvider::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function getJobInstance()
    {
        return new Job(
            self::$account,
            DateTime::createFromFormat(
                'Y-m-d H:i:s',
                '2017-08-07 14:10:00'
            ),
            RegionProvider::factory('us-east-1'),
            'key',
            'secret'
        );
    }

    /**
     * @test
     */
    public function processJobWithOutBuckets()
    {
        $resultSet = $this->getResultSetInstance();

        $job = $this->getJobInstance();

        $dataLoader = $this->getMockBuilder(
            'DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\S3\DataLoader'
        )->disableOriginalConstructor()
            ->setMethods(['retrieveBuckets'])
            ->getMock();

        $dataLoader->method('retrieveBuckets')
            ->willReturn($this->getEmptyBucketCollection());

        $glacier = new S3($dataLoader);
        $glacier->setJob($job);
        $glacier->process($resultSet);

        $this->assertEquals(0, count($resultSet->getJobs()));
        $this->assertEmpty($resultSet->getError());
    }

    /**
     * @test
     * @dataProvider getBucket
     */
    public function processJobWithData($bucket)
    {
        $resultSet = $this->getResultSetInstance();

        $job = $this->getJobInstance();
        $job->setData(new FilterCollection([
            new Filter(
                'S3',
                ['Versioning'],
                $bucket
            )
        ]));

        $dataLoader = $this->getMockBuilder(
            'DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\S3\DataLoader'
        )->disableOriginalConstructor()
            ->setMethods(['retrieveBuckets', 'updateBucketVersioning', 'updateBucketLocation', 'updateBucketTag'])
            ->getMock();

        $dataLoader->method('retrieveBuckets')
            ->willReturn($bucket);

        $dataLoader->method('updateBucketVersioning')
            ->willReturn($bucket);
        $dataLoader->method('updateBucketLocation')
            ->willReturn($bucket);
        $dataLoader->method('updateBucketTag')
            ->willReturn($bucket);

        $glacier = new S3($dataLoader);
        $glacier->setJob($job);
        $glacier->process($resultSet);

        $this->assertEmpty($resultSet->getError());
        $this->assertNotEmpty($resultSet->getMetrics());
    }

    /**
     * @test
     */
    public function getJobBuilderTest()
    {
        $this->assertInstanceOf(Builder::class, $this->s3->getJobBuilder());
    }

    private function getDataLoaderInstance(Job $job, CacheProvider $cache)
    {
        return new DataLoader(
            $job,
            $cache
        );
    }

    public function getBucket()
    {
        $tags = new TagCollection();
        $tags->add(new Tag('foo', 'bar'));
        $tags->add(new Tag('env', 'prod'));
        $tags->add(new Tag('env', 'prod'));
        $tags->add(new Tag('env', 'dev'));
        $tags->add(new Tag('env', 'homolog'));
        $tags->add(new Tag('env', 'prod'));

        return [
            [
                new Bucket(
                    'fivemetricsbucket',
                    new Versioning('Enabled'),
                    new Location('us-east-1'),
                    $tags
                )
            ],
            [
                new Bucket(
                    'fivemetricsbucket2',
                    new Versioning('Disabled'),
                    new Location('us-east-2'),
                    $tags
                )
            ]
        ];
    }

    private function getEmptyBucketCollection()
    {
        return new BucketCollection();
    }

    private function getResultSetInstance()
    {
        return new ResultSet(
            self::$account,
            new JobCollection(),
            new MetricCollection(),
            new MetadataCollection()
        );
    }
}
