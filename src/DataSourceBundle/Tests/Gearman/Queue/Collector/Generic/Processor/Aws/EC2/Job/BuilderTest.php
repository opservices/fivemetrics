<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 22/03/17
 * Time: 09:56
 */

namespace DataSourceBundle\Tests\Gearman\Queue\Collector\Generic\Processor\Aws\EC2\Job;

use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\EBS\FilterCollection as EBSFilterCollection;
use DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\EBS\Filter as EBSFilter;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScaling\Job\Job as AutoScalingJob;
use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\FilterCollection;
use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\JobCollection;
use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScalingGroup\FilterCollection
    as AutoScalingGroupFilterCollection;
use DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScalingGroup\Filter
    as AutoScalingGroupFilter;
use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer\FilterCollection
    as ELBFilterCollection;
use DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer\Filter as ELBFilter;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EBS\Job\Job as EBSJob;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer\Job\Job as ELBJob;
use EssentialsBundle\Entity\Account\Account;
use DataSourceBundle\Entity\Aws\Region\RegionProvider;
use DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\Filter;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EC2\Job\Builder;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EC2\Job\Job;
use EssentialsBundle\Entity\DateTime\DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Class BuilderTest
 * @package DataSourceBundle\Tests\Gearman\Queue\Collector\Generic\Processor\Aws\EC2\Job
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
     * @expectedException \InvalidArgumentException
     */
    public function buildJobToAnInvalidService()
    {
        $job = $this->getEC2JobTest();
        $filters = new FilterCollection([
            new Filter('invalid', [ 'test' ])
        ]);

        $this->builder->build($job, $filters);
    }

    /**
     * @test
     */
    public function buildJobEC2()
    {
        $baseJob = $this->getEC2JobTest();
        $filters = new FilterCollection([
            new Filter('EC2', [ 'InstanceState' ])
        ]);

        $expected = new JobCollection([ $this->getEC2JobTest() ]);
        $expected->at(0)->setData(clone($filters));

        $result = $this->builder->build($baseJob, $filters);

        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function buildJobAutoScaling()
    {
        $baseJob = $this->getEC2JobTest();
        $filters = new FilterCollection([
            new Filter('AutoScaling', [ 'InstanceState' ])
        ]);

        $expectedFilters = new AutoScalingGroupFilterCollection([
            new AutoScalingGroupFilter('AutoScaling', [ 'InstanceState' ])
        ]);

        $expected = new JobCollection([ $this->getAutoScalingJobTest() ]);
        $expected->at(0)->setData(clone($expectedFilters));

        $result = $this->builder->build($baseJob, $filters);

        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function buildJobELB()
    {
        $baseJob = $this->getEC2JobTest();
        $filters = new FilterCollection([
            new Filter('ElasticLoadBalancer', [ 'InstanceState' ])
        ]);

        $expectedFilters = new ELBFilterCollection([
            new ELBFilter('ElasticLoadBalancer', [ 'InstanceState' ])
        ]);

        $expected = new JobCollection([ $this->getELBJobTest() ]);
        $expected->at(0)->setData(clone($expectedFilters));

        $result = $this->builder->build($baseJob, $filters);

        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function buildJobEBS()
    {
        $baseJob = $this->getEC2JobTest();
        $filters = new FilterCollection([
            new Filter('EBS', [ 'Volumes' ])
        ]);

        $expectedFilters = new EBSFilterCollection([
            new EBSFilter('EBS', [ 'Volumes' ])
        ]);

        $expected = new JobCollection([ $this->getEBSJobTest() ]);
        $expected->at(0)->setData(clone($expectedFilters));

        $result = $this->builder->build($baseJob, $filters);

        $this->assertEquals($expected, $result);
    }

    /**
     * @return Job
     */
    public function getEC2JobTest(): Job
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

    /**
     * @return AutoScalingJob
     */
    public function getAutoScalingJobTest(): AutoScalingJob
    {
        $account = new Account();
        $account->setUid('test');

        return new AutoScalingJob(
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

    /**
     * @return ELBJob
     */
    protected function getELBJobTest(): ELBJob
    {
        $account = new Account();
        $account->setUid('test');

        return new ELBJob(
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

    /**
     * @return EBSJob
     */
    protected function getEBSJobTest(): EBSJob
    {
        $account = new Account();
        $account->setUid('test');

        return new EBSJob(
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
}
