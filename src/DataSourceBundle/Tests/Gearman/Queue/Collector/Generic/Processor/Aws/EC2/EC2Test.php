<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 22/03/17
 * Time: 14:36
 */

namespace DataSourceBundle\Tests\Gearman\Queue\Collector\Generic\Processor\Aws\EC2;

use DataSourceBundle\Collection\Aws\EC2\BlockDeviceMappingCollection;
use DataSourceBundle\Collection\Aws\EC2\Instance\InstanceCollection;
use DataSourceBundle\Collection\Aws\EC2\Instance\InstanceIndexer;
use DataSourceBundle\Collection\Aws\EC2\NetworkInterfaceCollection;
use DataSourceBundle\Collection\Aws\EC2\ProductCodeCollection;
use DataSourceBundle\Collection\Aws\EC2\Reservation\Instance\RecurringChargesCollection;
use DataSourceBundle\Collection\Aws\EC2\Reservation\Instance\ReservationCollection;
use DataSourceBundle\Collection\Aws\EC2\SecurityGroupCollection;
use DataSourceBundle\Collection\Aws\EC2\Subnet\Ipv6\Ipv6CidrBlockAssociationCollection;
use DataSourceBundle\Collection\Aws\EC2\Subnet\SubnetCollection;
use DataSourceBundle\Collection\Aws\Tag\TagCollection;
use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\FilterCollection;
use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\JobCollection;
use DataSourceBundle\Entity\Aws\EC2\Instance\IamInstanceProfile;
use DataSourceBundle\Entity\Aws\EC2\Instance\Instance;
use DataSourceBundle\Entity\Aws\EC2\Instance\InstanceState;
use DataSourceBundle\Entity\Aws\EC2\Instance\Monitoring;
use DataSourceBundle\Entity\Aws\EC2\Instance\Placement;
use DataSourceBundle\Entity\Aws\EC2\Instance\ProductCode;
use DataSourceBundle\Entity\Aws\EC2\Instance\StateReason;
use DataSourceBundle\Entity\Aws\EC2\Reservation\Instance\RecurringCharges;
use DataSourceBundle\Entity\Aws\EC2\Reservation\Instance\Reservation;
use DataSourceBundle\Entity\Aws\EC2\Subnet\Subnet;
use DataSourceBundle\Entity\Aws\Region\RegionProvider;
use DataSourceBundle\Entity\Aws\Tag\Tag;
use DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\Filter;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EC2\DataLoader;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EC2\EC2;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EC2\Job\Builder;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EC2\Job\Job;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer\Job\Job as ELBJob;
use DataSourceBundle\Gearman\Queue\Collector\Generic\ResultSet;
use Doctrine\Common\Cache\CacheProvider;
use EssentialsBundle\Collection\Metric\MetricCollection;
use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Entity\DateTime\DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Class EC2Test
 * @package DataSourceBundle\Tests\Gearman\Queue\Collector\Generic\Processor\Aws\EC2
 */
class EC2Test extends TestCase
{
    /**
     * @var EC2
     */
    protected $ec2Processor;

    public function setUp()
    {
        $this->ec2Processor = new EC2(null, new Builder());
        $this->ec2Processor->setJob($this->getEC2Job());
    }

    public function getResultSetInstance()
    {
        $account = new Account();
        $account->setUid('test');

        return new ResultSet(
            $account,
            new JobCollection(),
            new MetricCollection(),
            null
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

        $this->ec2Processor->setJob(
            new ELBJob(
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
        /** @var CacheProvider $cache */
        $cache = $this
            ->getMockBuilder(CacheProvider::class)
            ->disableOriginalConstructor()
            ->getMock();

        $job = $this->getEC2Job();
        $dataLoader = $this->getDataLoaderInstance($job, $cache);

        $ec2Processor = new EC2($dataLoader);

        $loader1 = $ec2Processor->getDataLoader();
        $loader2 = $ec2Processor->getDataLoader();
        $this->assertSame($loader1, $loader2);
    }

    /**
     * @test
     */
    public function processInvalidFilter()
    {
        $resultSet = $this->getResultSetInstance();

        $this->ec2Processor->getJob()->setData(new FilterCollection([
            new Filter('EC2', [ 'junk' ])
        ]));

        $this->ec2Processor->process($resultSet);

        $this->assertEmpty($resultSet->getData());
        $this->assertEmpty($resultSet->getError());
        $this->assertEmpty($resultSet->getJobs());
        $this->assertEmpty($resultSet->getMetrics());
    }

    /**
     * @test
     */
    public function processInstanceMetricsFilter()
    {
        $resultSet = $this->getResultSetInstance();

        $dataLoader = $this
            ->getMockBuilder(DataLoader::class)
            ->disableOriginalConstructor()
            ->setMethods([ 'retrieveInstances' ])
            ->getMock();

        $dataLoader->expects($this->once())
            ->method('retrieveInstances')
            ->will($this->returnValue($this->getEC2InstancesTest()));

        $ec2Processor = new EC2($dataLoader);
        $ec2Processor->setJob($this->getEC2Job());
        $ec2Processor->getJob()->setData(new FilterCollection([
            new Filter(
                'EC2',
                [ 'Instances' ]
            )
        ]));

        $ec2Processor->process($resultSet);

        $this->assertEquals(0, count($resultSet->getData()));
        $this->assertEmpty($resultSet->getError());
        $this->assertEmpty($resultSet->getJobs());
        $this->assertEquals(1, count($resultSet->getMetrics()));
    }

    /**
     * @test
     */
    public function processInstanceMetricsFilterWithoutApiData()
    {
        $resultSet = $this->getResultSetInstance();

        $dataLoader = $this
            ->getMockBuilder(DataLoader::class)
            ->disableOriginalConstructor()
            ->setMethods([ 'retrieveInstances' ])
            ->getMock();

        $dataLoader->expects($this->once())
            ->method('retrieveInstances')
            ->will($this->returnValue(new InstanceCollection()));

        $ec2Processor = new EC2($dataLoader);
        $ec2Processor->setJob($this->getEC2Job());
        $ec2Processor->getJob()->setData(new FilterCollection([
            new Filter(
                'EC2',
                [ 'Instances' ]
            )
        ]));

        $ec2Processor->process($resultSet);

        $this->assertEmpty($resultSet->getData());
        $this->assertEmpty($resultSet->getError());
        $this->assertEmpty($resultSet->getJobs());
        $this->assertEmpty($resultSet->getMetrics());
    }

    /**
     * @test
     */
    public function processReservationMetricsFilter()
    {
        $resultSet = $this->getResultSetInstance();

        $dataLoader = $this
            ->getMockBuilder(DataLoader::class)
            ->disableOriginalConstructor()
            ->setMethods([ 'retrieveInstances', 'retrieveReservedInstances' ])
            ->getMock();

        $dataLoader->expects($this->once())
            ->method('retrieveInstances')
            ->will($this->returnValue($this->getEC2InstancesTest()));

        $dataLoader->expects($this->once())
            ->method('retrieveReservedInstances')
            ->will($this->returnValue($this->getReservationsTest()));

        $ec2Processor = new EC2($dataLoader);
        $ec2Processor->setJob($this->getEC2Job());
        $ec2Processor->getJob()->setData(new FilterCollection([
            new Filter(
                'EC2',
                [ 'Reserves' ]
            )
        ]));

        $ec2Processor->process($resultSet);

        $this->assertEquals(1, count($resultSet->getData()));
        $this->assertEmpty($resultSet->getError());
        $this->assertEmpty($resultSet->getJobs());
        $this->assertEquals(2, count($resultSet->getMetrics()));
    }

    public function getEC2Job(): Job
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

    public function getEC2InstancesTest(): InstanceCollection
    {
        return new InstanceCollection(
            [
                new Instance(
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
                    new TagCollection([new Tag('Name', 'test')]),
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
                )
            ],
            new InstanceIndexer()
        );
    }

    public function getSubnetsTest(): SubnetCollection
    {
        return new SubnetCollection([ new Subnet(
            'vpcId',
            'subnetId',
            'pending',
            'availabilityZone',
            1,
            'cidrBlock',
            false,
            false,
            false,
            new TagCollection(),
            new Ipv6CidrBlockAssociationCollection()
        )]);
    }

    public function getReservationsTest(): ReservationCollection
    {
        return new ReservationCollection([new Reservation(
            'test',
            'c4.large',
            DateTime::createFromFormat('Y-m-d H:i', '2017-03-17 09:05'),
            DateTime::createFromFormat('Y-m-d H:i', '2018-03-17 09:05'),
            31536000,
            0.01,
            0,
            2,
            'Linux/UNIX',
            'active',
            'default',
            'USD',
            'Partial Upfront',
            new RecurringChargesCollection([ new RecurringCharges(0.01, 'Hourly') ]),
            'standard',
            'Region',
            null,
            new TagCollection([ new Tag('unit', 'test') ])
        ), new Reservation(
            'test',
            'c4.large',
            DateTime::createFromFormat('Y-m-d H:i', '2017-03-17 09:05'),
            DateTime::createFromFormat('Y-m-d H:i', '2018-03-17 09:05'),
            31536000,
            0.01,
            0,
            2,
            'Linux/UNIX',
            'retired',
            'default',
            'USD',
            'Partial Upfront',
            new RecurringChargesCollection([ new RecurringCharges(0.01, 'Hourly') ]),
            'standard',
            'Region',
            '',
            new TagCollection([ new Tag('unit', 'test') ])
        )]);
    }
}
