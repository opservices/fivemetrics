<?php
/**
* Created by PhpStorm.
* User: fivemetrics
 * Date: 21/03/17
 * Time: 14:18
 */

namespace DataSourceBundle\Tests\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScaling;

use DataSourceBundle\Collection\Aws\AutoScaling\ActivityCollection;
use DataSourceBundle\Collection\Aws\AutoScaling\AutoScalingGroupCollection;
use DataSourceBundle\Collection\Aws\AutoScaling\EnabledMetricCollection;
use DataSourceBundle\Collection\Aws\AutoScaling\InstanceCollection;
use DataSourceBundle\Collection\Aws\AutoScaling\SuspendedProcessCollection;
use DataSourceBundle\Collection\Aws\AutoScaling\TagCollection;
use DataSourceBundle\Collection\Gearman\Metadata\MetadataCollection;
use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScalingGroup\FilterCollection;
use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\JobCollection;
use DataSourceBundle\Entity\Aws\AutoScaling\Activity;
use DataSourceBundle\Entity\Aws\AutoScaling\AutoScalingGroup;
use DataSourceBundle\Entity\Aws\AutoScaling\Instance;
use DataSourceBundle\Entity\Aws\Region\RegionProvider;
use DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScalingGroup\Filter;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScaling\AutoScaling;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScaling\DataLoader;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScaling\Filter\GroupingResolver;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScaling\Job\Builder;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScaling\Job\Job;
use DataSourceBundle\Gearman\Queue\Collector\Generic\ResultSet;
use Doctrine\Common\Cache\CacheProvider;
use EssentialsBundle\Collection\Metric\MetricCollection;
use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Entity\DateTime\DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Class AutoScalingTest
 * @package DataSourceBundle\Tests\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScaling
 */
class AutoScalingTest extends TestCase
{
    /**
     * @var AutoScaling
     */
    protected $autoScaling;

    protected static $account;

    public static function setUpBeforeClass()
    {
        self::$account = new Account();
        self::$account->setUid('test');
    }

    public function setUp()
    {
        $this->autoScaling = new AutoScaling();
        $this->autoScaling->setJob(new Job(
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
        $this->autoScaling->setJob(
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
        $cache = $this
            ->getMockBuilder(CacheProvider::class)
            ->disableOriginalConstructor()
            ->getMock();

        $job = $this->getJobInstance();
        $dataLoader = $this->getDataLoaderInstance($job, $cache);

        $autoScaling = new AutoScaling($dataLoader);

        $loader1 = $autoScaling->getDataLoader();
        $loader2 = $autoScaling->getDataLoader();

        $this->assertSame($loader1, $loader2);
    }

    /**
     * @test
     */
    public function processInvalidFilter()
    {
        $resultSet = $this->getResultSetInstance();

        $job = $this->getJobInstance();
        $job->setData(new FilterCollection([
               new Filter('AutoScaling', [ 'junk' ])
        ]));

        $cache = $this
            ->getMockBuilder(CacheProvider::class)
            ->disableOriginalConstructor()
            ->getMock();

        $dataLoader = $this->getDataLoaderInstance($job, $cache);
        $autoScaling = new AutoScaling($dataLoader);

        $autoScaling->setJob($job);
        $autoScaling->process($resultSet);

        $this->assertEmpty($resultSet->getData());
        $this->assertEmpty($resultSet->getError());
        $this->assertEmpty($resultSet->getJobs());
        $this->assertEmpty($resultSet->getMetrics());
    }

    /**
     * @test
     */
    public function processJobWithActivityFilter()
    {
        $resultSet = $this->getResultSetInstance();

        $filters = new FilterCollection([
            new Filter(
                'AutoScaling',
                [ 'Activities' ],
                null,
                new AutoScalingGroup(
                    "autoScalingGroupName",
                    "autoScalingGroupARN",
                    "launchConfigurationName",
                    2,
                    4,
                    3,
                    300,
                    [ "az1", "az2" ],
                    [ "lb1", "lb2" ],
                    [ "targetGroupARNs" ],
                    "healthCheckType",
                    60,
                    DateTime::createFromFormat('Y-m-d H:i', '2017-02-16 14:17'),
                    "VPCZoneIdentifier",
                    [ "terminationPolicies" ],
                    false,
                    new InstanceCollection(),
                    new SuspendedProcessCollection(),
                    new EnabledMetricCollection(),
                    new TagCollection(),
                    new ActivityCollection()
                )
            )
        ]);

        $groupResolver = $this
            ->getMockBuilder(GroupingResolver::class)
            ->setMethods([ 'splitMeasurements' ])
            ->getMock();

        $groupResolver->expects($this->once())
            ->method('splitMeasurements')
            ->will($this->returnValue($filters));

        $dataLoader = $this
            ->getMockBuilder(DataLoader::class)
            ->disableOriginalConstructor()
            ->setMethods([ 'retrieveAutoScalingActivities' ])
            ->getMock();

        $dataLoader->expects($this->once())
            ->method('retrieveAutoScalingActivities')
            ->will($this->returnValue(new ActivityCollection(
                [
                    new Activity(
                        "activityId",
                        "autoScalingGroupName",
                        "description",
                        "cause",
                        DateTime::createFromFormat('Y-m-d H:i', '2017-02-21 15:08'),
                        DateTime::createFromFormat('Y-m-d H:i', '2017-02-21 15:09'),
                        "PendingSpotBidPlacement",
                        10,
                        "details"
                    )
                ]
            )));

        $jobs = new JobCollection([ $this->autoScaling->getJob() ]);
        $jobs->at(0)->setData($filters);

        $jobBuilder = $this
            ->getMockBuilder(Builder::class)
            ->setMethods([ 'build' ])
            ->getMock();

        $jobBuilder->expects($this->once())
            ->method('build')
            ->will($this->returnValue($jobs));

        $autoScaling = new AutoScaling(
            $dataLoader,
            $groupResolver,
            $jobBuilder
        );

        $autoScaling->setJob($jobs->at(0));
        $autoScaling->process($resultSet);

        $this->assertEquals(1, count($resultSet->getData()));
        $this->assertEmpty($resultSet->getError());
        $this->assertEmpty($resultSet->getJobs());
        $this->assertEmpty($resultSet->getMetrics());
    }

    /**
     * @test
     */
    public function processJobFilterWithoutAutoScalingGroups()
    {
        $resultSet = $this->getResultSetInstance();

        $filters = new FilterCollection([
            new Filter(
                'AutoScaling',
                [ 'InstanceState', 'Instance' ]
            )
        ]);

        $groupResolver = $this
            ->getMockBuilder(GroupingResolver::class)
            ->setMethods([ 'splitMeasurements' ])
            ->getMock();

        $groupResolver->expects($this->once())
            ->method('splitMeasurements')
            ->will($this->returnValue($filters));

        $dataLoader = $this
            ->getMockBuilder(DataLoader::class)
            ->disableOriginalConstructor()
            ->setMethods([ 'retrieveAutoScalingGroups' ])
            ->getMock();

        $dataLoader->expects($this->once())
            ->method('retrieveAutoScalingGroups')
            ->will($this->returnValue(new AutoScalingGroupCollection()));

        $jobs = new JobCollection([ $this->autoScaling->getJob() ]);
        $jobs->at(0)->setData($filters);

        $jobBuilder = $this
            ->getMockBuilder(Builder::class)
            ->setMethods([ 'build' ])
            ->getMock();

        $jobBuilder->expects($this->once())
            ->method('build')
            ->will($this->returnValue($jobs));

        $autoScaling = new AutoScaling(
            $dataLoader,
            $groupResolver,
            $jobBuilder
        );

        $autoScaling->setJob($jobs->at(0));
        $autoScaling->process($resultSet);

        $this->assertEmpty($resultSet->getData());
        $this->assertEmpty($resultSet->getError());
        $this->assertEmpty($resultSet->getJobs());
        $this->assertEmpty($resultSet->getMetrics());
    }

    /**
     * @test
     */
    public function processJobFilter()
    {
        $resultSet = $this->getResultSetInstance();

        $autoScalingGroupCollection = new AutoScalingGroupCollection([
            new AutoScalingGroup(
                "autoScalingGroupName",
                "autoScalingGroupARN",
                "launchConfigurationName",
                2,
                4,
                3,
                300,
                [ "az1", "az2" ],
                [ "lb1", "lb2" ],
                [ "targetGroupARNs" ],
                "healthCheckType",
                60,
                DateTime::createFromFormat('Y-m-d H:i', '2017-02-16 14:17'),
                "VPCZoneIdentifier",
                [ "terminationPolicies" ],
                false,
                new InstanceCollection([
                    new Instance(
                        'id',
                        'az1',
                        'InService',
                        'healthStatus',
                        'launchConfigurationName',
                        false,
                        'autoScalingGroupName'
                    )
                ]),
                new SuspendedProcessCollection(),
                new EnabledMetricCollection(),
                new TagCollection(),
                new ActivityCollection()
            )
        ]);

        $filters = new FilterCollection([
            new Filter(
                'AutoScaling',
                [ 'Instances' ]
            )
        ]);

        $groupResolver = $this
            ->getMockBuilder(GroupingResolver::class)
            ->setMethods([ 'splitMeasurements' ])
            ->getMock();

        $groupResolver->expects($this->once())
            ->method('splitMeasurements')
            ->will($this->returnValue($filters));

        $dataLoader = $this
            ->getMockBuilder(DataLoader::class)
            ->disableOriginalConstructor()
            ->setMethods([ 'retrieveAutoScalingGroups' ])
            ->getMock();

        $dataLoader->expects($this->once())
            ->method('retrieveAutoScalingGroups')
            ->will($this->returnValue($autoScalingGroupCollection));

        $jobs = new JobCollection([ $this->autoScaling->getJob() ]);
        $jobs->at(0)->setData($filters);

        $jobBuilder = $this
            ->getMockBuilder(Builder::class)
            ->setMethods([ 'build' ])
            ->getMock();

        $jobBuilder->expects($this->once())
            ->method('build')
            ->will($this->returnValue($jobs));

        $autoScaling = new AutoScaling(
            $dataLoader,
            $groupResolver,
            $jobBuilder
        );

        $autoScaling->setJob($jobs->at(0));
        $autoScaling->process($resultSet);

        $this->assertEquals(1, count($resultSet->getMetrics()));
        $this->assertEmpty($resultSet->getError());
        $this->assertEmpty($resultSet->getJobs());
    }
}
