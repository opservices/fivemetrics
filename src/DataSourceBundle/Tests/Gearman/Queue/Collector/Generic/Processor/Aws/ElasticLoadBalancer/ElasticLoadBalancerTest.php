<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 22/03/17
 * Time: 16:55
 */

namespace DataSourceBundle\Tests\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer;

use DataSourceBundle\Collection\Aws\EC2\BlockDeviceMappingCollection;
use DataSourceBundle\Collection\Aws\EC2\Instance\InstanceCollection as EC2InstanceCollection;
use DataSourceBundle\Collection\Aws\EC2\NetworkInterfaceCollection;
use DataSourceBundle\Collection\Aws\EC2\ProductCodeCollection;
use DataSourceBundle\Collection\Aws\EC2\SecurityGroupCollection;
use DataSourceBundle\Collection\Aws\ElasticLoadBalancer\BackendServerDescriptionCollection;
use DataSourceBundle\Collection\Aws\ElasticLoadBalancer\ElasticLoadBalancerCollection;
use DataSourceBundle\Collection\Aws\ElasticLoadBalancer\InstanceCollection;
use DataSourceBundle\Collection\Aws\ElasticLoadBalancer\InstanceHealthCollection;
use DataSourceBundle\Collection\Aws\ElasticLoadBalancer\ListenerDescription\ListenerDescriptionCollection;
use DataSourceBundle\Collection\Aws\ElasticLoadBalancer\Policy\AppCookieStickinessPolicyCollection;
use DataSourceBundle\Collection\Aws\ElasticLoadBalancer\Policy\LBCookieStickinessPolicyCollection;
use DataSourceBundle\Collection\Aws\Tag\TagCollection;
use DataSourceBundle\Collection\Gearman\Job\JobCollection;
use DataSourceBundle\Collection\Gearman\Metadata\MetadataCollection;
use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer\FilterCollection;
use DataSourceBundle\Entity\Aws\EC2\Instance\IamInstanceProfile;
use DataSourceBundle\Entity\Aws\EC2\Instance\Instance;
use DataSourceBundle\Entity\Aws\EC2\Instance\InstanceState;
use DataSourceBundle\Entity\Aws\EC2\Instance\Monitoring;
use DataSourceBundle\Entity\Aws\EC2\Instance\Placement;
use DataSourceBundle\Entity\Aws\EC2\Instance\ProductCode;
use DataSourceBundle\Entity\Aws\EC2\Instance\StateReason;
use DataSourceBundle\Entity\Aws\ElasticLoadBalancer\ElasticLoadBalancer as ElasticLoadBalancerEntity;
use DataSourceBundle\Entity\Aws\ElasticLoadBalancer\HealthCheck;
use DataSourceBundle\Entity\Aws\ElasticLoadBalancer\InstanceHealth;
use DataSourceBundle\Entity\Aws\ElasticLoadBalancer\Policies\Policies;
use DataSourceBundle\Entity\Aws\ElasticLoadBalancer\SourceSecurityGroup;
use DataSourceBundle\Entity\Aws\Region\RegionProvider;
use DataSourceBundle\Entity\Aws\Tag\Tag;
use DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer\Filter;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer\DataLoader;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer\ElasticLoadBalancer;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer\Job\Job;
use DataSourceBundle\Gearman\Queue\Collector\Generic\ResultSet;
use Doctrine\Common\Cache\CacheProvider;
use EssentialsBundle\Collection\Metric\MetricCollection;
use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Entity\DateTime\DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Class ElasticLoadBalancerTest
 * @package DataSourceBundle\Tests\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer
 */
class ElasticLoadBalancerTest extends TestCase
{
    /**
     * @var ElasticLoadBalancer
     */
    protected $elbProcessor;

    public function setUp()
    {
        $account = new Account();
        $account->setUid('test');

        $this->elbProcessor = new ElasticLoadBalancer();
        $this->elbProcessor->setJob(new Job(
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


    public function getDataLoaderInstance(Job $job, CacheProvider $cache)
    {
        return new DataLoader(
            $job,
            $cache
        );
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function setInvalidJob()
    {
        $account = new Account();
        $account->setUid('test');

        $this->elbProcessor->setJob(
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
        $cache = $this->getMockBuilder('Doctrine\Common\Cache\CacheProvider')
            ->disableOriginalConstructor()
            ->getMock();

        $job = $this->getELBJob();
        $dataLoader = $this->getDataLoaderInstance($job, $cache);

        $elbProcessor = new ElasticLoadBalancer($dataLoader);

        $loader1 = $elbProcessor->getDataLoader();
        $loader2 = $elbProcessor->getDataLoader();
        $this->assertSame($loader1, $loader2);
    }

    /**
     * @test
     */
    public function processInvalidFilter()
    {
        $resultSet = $this->getResultSetInstance();

        $job = $this->getELBJob();
        $job->setData(new FilterCollection([
            new Filter('ElasticLoadBalancer', [ 'junk' ])
        ]));

        $cache = $this
            ->getMockBuilder(CacheProvider::class)
            ->disableOriginalConstructor()
            ->getMock();

        $dataLoader = $this->getDataLoaderInstance($job, $cache);
        $elbProcessor = new ElasticLoadBalancer($dataLoader);
        $elbProcessor->setJob($job);

        $elbProcessor->process($resultSet);

        $this->assertEmpty($resultSet->getData());
        $this->assertEmpty($resultSet->getError());
        $this->assertEmpty($resultSet->getJobs());
        $this->assertEmpty($resultSet->getMetrics());
    }

    /**
     * @test
     */
    public function processELBMetricsFilter()
    {
        $resultSet = $this->getResultSetInstance();

        $dataLoader = $this
            ->getMockBuilder(DataLoader::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'retrieveElasticLoadBalancers',
                'retrieveElasticLoadBalancerInstanceHealth',
                'retrieveInstances'
            ])
            ->getMock();

        $dataLoader->expects($this->once())
            ->method('retrieveElasticLoadBalancers')
            ->will($this->returnValue($this->getELBsInstancesTest()));

        $dataLoader->expects($this->once())
            ->method('retrieveElasticLoadBalancerInstanceHealth')
            ->will($this->returnValue($this->getELBInstanceHealth()));

        $dataLoader->expects($this->once())
            ->method('retrieveInstances')
            ->will($this->returnValue($this->getEC2InstancesTest()));

        $elbProcessor = new ElasticLoadBalancer($dataLoader);
        $elbProcessor->setJob($this->getELBJob());
        $elbProcessor->getJob()->setData(new FilterCollection([
            new Filter(
                'ElasticLoadBalancer',
                [ 'Instances' ]
            )
        ]));

        $elbProcessor->process($resultSet);

        $this->assertEmpty($resultSet->getError());
        $this->assertEmpty($resultSet->getJobs());
        $this->assertEquals(1, count($resultSet->getMetrics()));
    }

    public function getELBsInstancesTest(): ElasticLoadBalancerCollection
    {
        return new ElasticLoadBalancerCollection([new ElasticLoadBalancerEntity(
            "name",
            "dns",
            "canonicalId",
            new ListenerDescriptionCollection(),
            new Policies(
                new AppCookieStickinessPolicyCollection(),
                new LBCookieStickinessPolicyCollection(),
                []
            ),
            new BackendServerDescriptionCollection(),
            [],
            [],
            new InstanceCollection(),
            new HealthCheck("target", 60, 30, 10, 20),
            new SourceSecurityGroup("owner", "groupName"),
            [],
            DateTime::createFromFormat('Y-m-d H:i', '2017-03-22 17:21'),
            "scheme",
            "vpcId",
            "canonicalName",
            new InstanceHealthCollection()
        )]);
    }

    public function getELBJob(): Job
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

    public function getELBInstanceHealth()
    {
        return new InstanceHealthCollection([new InstanceHealth(
            'i-0db441fbe040a2efc',
            'InService',
            '',
            ''
        )]);
    }

    public function getEC2InstancesTest(): EC2InstanceCollection
    {
        return new EC2InstanceCollection(
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
            ]
        );
    }
}
