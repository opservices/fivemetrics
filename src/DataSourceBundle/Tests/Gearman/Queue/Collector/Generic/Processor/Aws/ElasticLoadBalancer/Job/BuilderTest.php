<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 21/03/17
 * Time: 08:18
 */

namespace DataSourceBundle\Tests\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer\Job;

use DataSourceBundle\Collection\Aws\ElasticLoadBalancer\BackendServerDescriptionCollection;
use DataSourceBundle\Collection\Aws\ElasticLoadBalancer\ElasticLoadBalancerCollection;
use DataSourceBundle\Collection\Aws\ElasticLoadBalancer\InstanceCollection;
use DataSourceBundle\Collection\Aws\ElasticLoadBalancer\InstanceHealthCollection;
use DataSourceBundle\Collection\Aws\ElasticLoadBalancer\ListenerDescription\ListenerDescriptionCollection;
use DataSourceBundle\Collection\Aws\ElasticLoadBalancer\Policy\AppCookieStickinessPolicyCollection;
use DataSourceBundle\Collection\Aws\ElasticLoadBalancer\Policy\LBCookieStickinessPolicyCollection;
use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer\FilterCollection;
use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\JobCollection;
use EssentialsBundle\Entity\Account\Account;
use DataSourceBundle\Entity\Aws\Region\RegionProvider;
use DataSourceBundle\Entity\Aws\ElasticLoadBalancer\ElasticLoadBalancer;
use DataSourceBundle\Entity\Aws\ElasticLoadBalancer\HealthCheck;
use DataSourceBundle\Entity\Aws\ElasticLoadBalancer\Policies\Policies;
use DataSourceBundle\Entity\Aws\ElasticLoadBalancer\SourceSecurityGroup;
use EssentialsBundle\Entity\DateTime\DateTime;
use DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer\Filter;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer\Job\Builder;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer\Job\Job;
use PHPUnit\Framework\TestCase;

/**
 * Class BuilderTest
 * @package DataSourceBundle\Tests\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer\Job
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
    public function buildJobForOneFilterWithData()
    {
        $job = $this->getELBJobTest();

        $filters = new FilterCollection([ new Filter(
            'ElasticLoadBalancer',
            [ 'InstanceState' ],
            null,
            $this->getELBsTest()
        ) ]);

        $dataLoader = $this->getMockBuilder(
            'DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer\DataLoader'
        )->disableOriginalConstructor()
        ->getMock();

        $expectedResult = new JobCollection([
            $this->getELBJobTest()
        ]);

        $this->assertEquals(
            $expectedResult,
            $this->builder->build($dataLoader, $job, $filters)
        );
    }

    /**
     * @test
     */
    public function buildJobForOneFilterWithoutData()
    {
        $job = $this->getELBJobTest();

        $filters = new FilterCollection([
            new Filter(
                'ElasticLoadBalancer',
                [ 'Instances' ]
            )
        ]);

        $dataLoader = $this->getMockBuilder(
            'DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer\DataLoader'
        )
            ->disableOriginalConstructor()
            ->setMethods([ 'retrieveElasticLoadBalancers' ])
            ->getMock();

        $dataLoader->expects($this->once())
            ->method('retrieveElasticLoadBalancers')
            ->will($this->returnValue($this->getELBsTest()));

        $resultFilters = new FilterCollection([
            new Filter(
                'ElasticLoadBalancer',
                [ 'Instances' ],
                $this->getELBsTest()
            )
        ]);

        $resultJob = $this->getELBJobTest()
            ->setData($resultFilters);

        $expectedResult = new JobCollection([ $resultJob ]);

        $this->assertEquals(
            $expectedResult,
            $this->builder->build($dataLoader, $job, $filters)
        );
    }

    public function getELBJobTest()
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
            'test',
            'unit'
        );
    }

    public function getELBsTest()
    {
        return new ElasticLoadBalancerCollection([new ElasticLoadBalancer(
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
            DateTime::createFromFormat('Y-m-d H:i', '2017-03-22 16:41'),
            "scheme",
            "vpcId",
            "canonicalName",
            new InstanceHealthCollection()
        )]);
    }
}
