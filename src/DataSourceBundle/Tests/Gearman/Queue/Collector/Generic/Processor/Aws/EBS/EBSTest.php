<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 8/16/17
 * Time: 3:57 PM
 */

namespace DataSourceBundle\Tests\Gearman\Queue\Collector\Generic\Processor\Aws\EBS;

use DataSourceBundle\Collection\Aws\EBS\Attachment\AttachmentCollection;
use DataSourceBundle\Collection\Aws\EBS\Volume\VolumeCollection;
use DataSourceBundle\Collection\Aws\EC2\BlockDeviceMappingCollection;
use DataSourceBundle\Collection\Aws\EC2\Instance\InstanceCollection;
use DataSourceBundle\Collection\Aws\EC2\NetworkInterfaceCollection;
use DataSourceBundle\Collection\Aws\EC2\ProductCodeCollection;
use DataSourceBundle\Collection\Aws\EC2\SecurityGroupCollection;
use DataSourceBundle\Collection\Aws\Tag\TagCollection;
use DataSourceBundle\Collection\Gearman\Job\JobCollection;
use DataSourceBundle\Collection\Gearman\Metadata\MetadataCollection;
use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\EBS\FilterCollection;
use DataSourceBundle\Entity\Aws\EBS\Volume\Volume;
use DataSourceBundle\Entity\Aws\EC2\Instance\IamInstanceProfile;
use DataSourceBundle\Entity\Aws\EC2\Instance\Instance;
use DataSourceBundle\Entity\Aws\EC2\Instance\InstanceState;
use DataSourceBundle\Entity\Aws\EC2\Instance\Monitoring;
use DataSourceBundle\Entity\Aws\EC2\Instance\Placement;
use DataSourceBundle\Entity\Aws\EC2\Instance\ProductCode;
use DataSourceBundle\Entity\Aws\EC2\Instance\StateReason;
use DataSourceBundle\Entity\Aws\EC2\Region\RegionProvider;
use DataSourceBundle\Entity\Aws\Tag\Tag;
use DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\EBS\Filter;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EBS\DataLoader;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EBS\EBS;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EBS\Filter\GroupingResolver;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EBS\Job\Job;
use DataSourceBundle\Gearman\Queue\Collector\Generic\ResultSet;
use Doctrine\Common\Cache\CacheProvider;
use EssentialsBundle\Collection\Metric\MetricCollection;
use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Entity\DateTime\DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Class EBSTest
 * @package DataSourceBundle\Tests\Gearman\Queue\Collector\Generic\Processor\Aws\EBS
 */
class EBSTest extends TestCase
{
    /**
     * @var EBS
     */
    protected $ebsProcessor;
    protected static $account;

    public static function setUpBeforeClass()
    {
        self::$account = new Account();
        self::$account->setUid('test');
    }

    public function setUp()
    {
        $account = new Account();
        $account->setUid('test');

        $this->ebsProcessor = new EBS();
        $this->ebsProcessor->setJob(new Job(
            $account,
            DateTime::createFromFormat(
                'Y-m-d H:i:s',
                '2017-08-07 14:10:00'
            ),
            RegionProvider::factory('us-east-1'),
            'key',
            'secret'
        ));
    }

    public function getResultSetInstance()
    {
        $account = new Account();
        $account->setUid('test');

        return new ResultSet(
            $account,
            new JobCollection(),
            new MetricCollection(),
            new MetadataCollection()
        );
    }


    public function getDataLoaderInstance(Job $job, CacheProvider $cacheProvider)
    {
        return new DataLoader($job, $cacheProvider);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function setInvalidJob()
    {
        $account = new Account();
        $account->setUid('test');

        $this->ebsProcessor->setJob(
            new \DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScaling\Job\Job(
                $account,
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
        $cacheProvider = $this->getMockBuilder('Doctrine\Common\Cache\CacheProvider')
            ->disableOriginalConstructor()
            ->getMock();

        $job = $this->getEBSJob();
        $dataLoader = $this->getDataLoaderInstance($job, $cacheProvider);

        $elbProcessor = new EBS($dataLoader);

        $loader1 = $elbProcessor->getDataLoader();
        $loader2 = $elbProcessor->getDataLoader();
        $this->assertSame($loader1, $loader2);
    }

    /**
     * @test
     */
    public function getNullDataLoader()
    {
        $this->assertInstanceOf(DataLoader::class, $this->ebsProcessor->getDataLoader());
    }


    /**
     * @test
     */
    public function getInstanceWithOutGroupingResolverAndJobBuilder()
    {
        $groupingResolver = new GroupingResolver();
        $glacier = new EBS(null, $groupingResolver);
        $glacier->setJob($this->getJobInstance());

        $this->assertInstanceOf(DataLoader::class, $glacier->getDataLoader());
        $this->assertInstanceOf(GroupingResolver::class, $glacier->getGroupResolver());
    }

    /**
     * @test
     * @dataProvider ebsDataProviderTest
     */
    public function processJobWithData($volumeCollection, $instances)
    {
        $resultSet = $this->getResultSetInstance();

        $job = $this->getJobInstance();
        $job->setData(new FilterCollection([
            new Filter(
                'EBS',
                ['Volumes']
            )
        ]));

        $dataLoader = $this->getMockBuilder(
            'DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EBS\DataLoader'
        )->disableOriginalConstructor()
            ->setMethods(['retrieveVolumes', 'retrieveInstances'])
            ->getMock();

        $dataLoader->method('retrieveVolumes')
            ->willReturn($volumeCollection);

        $dataLoader->method('retrieveInstances')
            ->willReturn($instances);

        $ebsProcessor = new EBS($dataLoader);
        $ebsProcessor->setJob($job);
        $ebsProcessor->process($resultSet);

        $this->assertEmpty($resultSet->getError());
        $this->assertNotEmpty($resultSet->getMetrics());
    }


    /**
     * @test
     */
    public function processJobWithOutData()
    {
        $resultSet = $this->getResultSetInstance();

        $job = $this->getJobInstance();
        $job->setData(new FilterCollection([
            new Filter(
                'EBS',
                ['Volumes']
            )
        ]));

        $dataLoader = $this->getMockBuilder(
            'DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EBS\DataLoader'
        )->disableOriginalConstructor()
            ->setMethods(['retrieveVolumes'])
            ->getMock();

        $dataLoader->method('retrieveVolumes')
            ->willReturn($this->getEmptyVolumeCollection());

        $ebsProcessor = new EBS($dataLoader);
        $ebsProcessor->setJob($job);
        $ebsProcessor->process($resultSet);

        $this->assertEmpty($resultSet->getError());
        $this->assertEmpty($resultSet->getMetrics());
        $this->assertEmpty($resultSet->getData());
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

    public function ebsDataProviderTest()
    {
        $volumeCollection = new VolumeCollection();

        $tag = new Tag('key', 'value');
        $tagCollection = new TagCollection();
        $tagCollection->add($tag);
        $volume1 = new Volume(
            'us-east-1b',
            new DateTime(),
            false,
            300,
            null,
            200,
            null,
            'in-use',
            new TagCollection(),
            'volume-id',
            'volume-type-id',
            new AttachmentCollection()
        );
        $volume2 = new Volume(
            'us-east-1a',
            new DateTime(),
            false,
            300,
            null,
            200,
            null,
            'in-use',
            $tagCollection,
            'id',
            'volume-id',
            null
        );

        $instances = new InstanceCollection([ $this->getEC2InstanceTest() ]);

        return [
            [ $volumeCollection->add($volume1), $instances ],
            [ $volumeCollection->add($volume2), $instances ],
        ];
    }

    private function getEmptyVolumeCollection()
    {
        return new VolumeCollection();
    }

    private function getEBSJob(): Job
    {
        $account = new Account();
        $account->setUid('test');

        return new Job(
            $account,
            DateTime::createFromFormat(
                'Y-m-d H:i:s',
                '2017-08-07 14:10:00'
            ),
            RegionProvider::factory('us-east-1'),
            'key',
            'secret'
        );
    }

    public function getEC2InstanceTest()
    {
        return new Instance(
            'i-0db441fbe040a2efc',
            'ami-6d1c2007',
            'PrivateDnsName',
            'PublicDnsName',
            'StateTransitionReason',
            0,
            'c4.large',
            'x86_64',
            'ebs',
            '/dev/sda1',
            'hvm',
            '148217644108981449',
            'xen',
            false,
            DateTime::createFromFormat('Y-m-d H:i', '2017-03-21 09:52'),
            new Monitoring('disabled'),
            new Placement('us-east-1a'),
            new InstanceState(16, 'running'),
            new TagCollection([ new Tag('Name', 'test') ]),
            new ProductCodeCollection([
                new ProductCode('aw0evgkw8e5c1q413zgy5pjce', 'marketplace')
            ]),
            new BlockDeviceMappingCollection(),
            new SecurityGroupCollection(),
            new NetworkInterfaceCollection(),
            'sriovNetSupport',
            'spotInstanceRequestId',
            'ramdiskId',
            'publicIpAddress',
            'Linux/Unix',
            'kernelId',
            'instanceLifecycle',
            false,
            'keyName',
            'subnetId',
            'vpcId',
            true,
            'privateIpAddress',
            new IamInstanceProfile('arn', 'id'),
            new StateReason('code', 'message')
        );
    }
}
