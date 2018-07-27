<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 8/3/17
 * Time: 3:43 PM
 */

namespace DataSourceBundle\Tests\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier;

use DataSourceBundle\Collection\Aws\Glacier\Vault\VaultCollection;
use DataSourceBundle\Collection\Gearman\Metadata\MetadataCollection;
use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier\FilterCollection;
use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\JobCollection;
use DataSourceBundle\Entity\Aws\Glacier\Job\Builder;
use DataSourceBundle\Entity\Aws\Glacier\Region\RegionProvider;
use DataSourceBundle\Entity\Aws\Glacier\Vault\Builder as VaultBuilder;
use DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier\Filter;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier\DataLoader;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier\Filter\GroupingResolver;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier\Glacier;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier\Job\Builder as JobBuilder;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier\Job\Job;
use DataSourceBundle\Gearman\Queue\Collector\Generic\ResultSet;
use Doctrine\Common\Cache\CacheProvider;
use EssentialsBundle\Collection\Metric\MetricCollection;
use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Entity\DateTime\DateTime;
use PHPUnit\Framework\TestCase;

class GlacierTest extends TestCase
{

    /**
     * @var Glacier
     */
    protected $glacier;

    protected static $account;

    public static function setUpBeforeClass()
    {
        self::$account = new Account();
        self::$account->setUid('test');
    }

    public function setUp()
    {
        $this->glacier = new Glacier();
        $this->glacier->setJob(new Job(
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

    public function getJobInstance()
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

    public function getDataLoaderInstance(Job $job, CacheProvider $cache)
    {
        return new DataLoader(
            $job,
            $cache
        );
    }

    public function getResultSetInstance()
    {
        return new ResultSet(
            self::$account,
            new JobCollection(),
            new MetricCollection(),
            new MetadataCollection()
        );
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function setInvalidJob()
    {
        $this->glacier->setJob(
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
    public function getDataLoader()
    {
        $cache = $this->getMockBuilder('Doctrine\Common\Cache\CacheProvider')
            ->disableOriginalConstructor()
            ->getMock();

        $job = $this->getJobInstance();
        $dataLoader = $this->getDataLoaderInstance($job, $cache);

        $glacier = new Glacier($dataLoader);

        $loader1 = $glacier->getDataLoader();
        $loader2 = $glacier->getDataLoader();

        $this->assertSame($loader1, $loader2);
    }

    /**
     * @test
     */
    public function getNullDataLoader()
    {
        $this->assertInstanceOf(DataLoader::class, $this->glacier->getDataLoader());
    }

    /**
     * @test
     */
    public function getInstanceWithOutGroupingResolverAndJobBuilder()
    {
        $groupingResolver = new GroupingResolver();
        $jobBuilder = new JobBuilder();
        $glacier = new Glacier(null, $groupingResolver, $jobBuilder);
        $glacier->setJob($this->getJobInstance());

        $this->assertInstanceOf(DataLoader::class, $glacier->getDataLoader());
        $this->assertInstanceOf(JobBuilder::class, $glacier->getJobBuilder());
        $this->assertInstanceOf(GroupingResolver::class, $glacier->getGroupResolver());
    }

    /**
     * @dataProvider getFilters
     * @test
     */
    public function processOneJobWithVault($filter)
    {
        $resultSet = $this->getResultSetInstance();

        $job = $this->getJobInstance();
        $job->setData(new FilterCollection([
            $filter
        ]));

        $dataLoader = $this->getMockBuilder(
            'DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier\DataLoader'
        )->disableOriginalConstructor()
            ->setMethods(['retrieveVaults', 'getJobsByVault', 'getTagsByVault'])
            ->getMock();

        $dataLoader->method('retrieveVaults')
            ->willReturn($this->getVaultsTest());

        $dataLoader->method('getJobsByVault')
            ->willReturn($this->getVaultJobTest());

        $dataLoader->method('getTagsByVault')
            ->willReturn($this->getVaultsTest()->at(0));

        $glacier = new Glacier($dataLoader);
        $this->getVaultJobTest();
        $glacier->setJob($job);
        $glacier->process($resultSet);

        $this->assertEquals(0, count($resultSet->getJobs()));
        $this->assertEmpty($resultSet->getError());
    }

    /**
     * @test
     */
    public function processOneJobWithOutVault()
    {
        $resultSet = $this->getResultSetInstance();

        $job = $this->getJobInstance();
        $job->setData(new FilterCollection([
            new Filter(
                'Glacier\Vault',
                ['VaultSize']
            )
        ]));

        $dataLoader = $this->getMockBuilder(
            'DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier\DataLoader'
        )->disableOriginalConstructor()
            ->setMethods(['retrieveVaults'])
            ->getMock();

        $dataLoader->method('retrieveVaults')
            ->willReturn($this->getEmptyVaultCollection());

        $glacier = new Glacier($dataLoader);
        $glacier->setJob($job);
        $glacier->process($resultSet);

        $this->assertEquals(0, count($resultSet->getJobs()));
        $this->assertEmpty($resultSet->getError());
    }

    /**
     * @test
     */
    public function processJobsWithOutData()
    {
        $resultSet = $this->getResultSetInstance();

        $job = $this->getJobInstance();

        $dataLoader = $this->getMockBuilder(
            'DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier\DataLoader'
        )->disableOriginalConstructor()
            ->getMock();

        $dataLoader->method('retrieveVaults')
            ->willReturn($this->getEmptyVaultCollection());

        $glacier = new Glacier($dataLoader);
        $glacier->setJob($job);
        $glacier->process($resultSet);

        $this->assertGreaterThan(0, count($resultSet->getJobs()));
        $this->assertEmpty($resultSet->getError());
        $this->assertEmpty($resultSet->getMetrics());
        $this->assertEmpty($resultSet->getData());
    }


    public function getFilters()
    {
        return [
            [
                new Filter(
                    'Glacier\Vault',
                    ['VaultSize']
                )
            ],
            [
                new Filter(
                    'Glacier\Vault',
                    ['VaultArchive']
                )
            ],
            [
                new Filter(
                    'Glacier\Job',
                    ['Job'],
                    $this->getVaultsTest()->at(0)
                )
            ]

        ];
    }

    public function getVaultsTest(): VaultCollection
    {
        $sizeInBytes = 1024;
        $numberOfArchives = 1;
        $tags = [
            [
                'Key' => 'foo',
                'Value' => 'bar'
            ]
        ];
        $data = [
            [
                'VaultName' => 'newVault',
                'NumberOfArchives' => $numberOfArchives,
                'SizeInBytes' => $sizeInBytes,
                'Tags' => $tags
            ]
        ];

        return VaultBuilder::build($data);
    }

    public function getVaultJobTest()
    {
        $vault = $this->getVaultsTest()->at(0);
        $jobs = [];
        $jobs[0]["JobId"] = "id";
        $jobs[0]["Action"] = 'InventoryRetrieval';
        $jobs[0]["VaultARN"] = 'arn:aws:glacier:us-east-1:239620292590:vaults/vaultfivemetrics';
        $jobs[0]["CreationDate"] = "2017-08-07T15:47:30.119Z";
        $jobs[0]["Completed"] = true;
        $jobs[0]["StatusCode"] = "Succeeded";
        $jobs[0]["CompletionDate"] = "2017-08-07T15:47:30.119Z";
        return Builder::build($jobs, $vault);
    }

    public function getEmptyVaultCollection()
    {
        return new VaultCollection();
    }
}
