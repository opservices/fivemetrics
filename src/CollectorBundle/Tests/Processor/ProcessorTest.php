<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 09/10/17
 * Time: 08:42
 */

namespace CollectorBundle\Tests\Processor;

use CollectorBundle\Collect\Collect;
use CollectorBundle\Collect\CollectBucket;
use CollectorBundle\Collect\CollectBucketBuilder;
use CollectorBundle\Collect\CollectBucketCollection;
use CollectorBundle\Job\JobBuilder;
use CollectorBundle\Processor\Processor;
use DataSourceBundle\Collection\Gearman\Job\JobCollection;
use DataSourceBundle\Collection\Gearman\Metadata\MetadataCollection;
use DataSourceBundle\Entity\Aws\Region\RegionProvider;
use DataSourceBundle\Gearman\Queue\Collector\Generic\ResultSet;
use EssentialsBundle\Collection\Metric\MetricCollection;
use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Entity\Builder\EntityBuilderProvider;
use EssentialsBundle\Reflection;
use GearmanBundle\TaskManager\TaskManager;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ProcessorTest extends KernelTestCase
{
    /**
     * @var Processor
     */
    protected $processor;

    /**
     * @var CollectBucketBuilder
     */
    protected $builder;

    public function setUp()
    {
        $this->builder = new CollectBucketBuilder(
            EntityBuilderProvider::factory(Account::class),
            new JobBuilder(new RegionProvider())
        );

        $tm = $this->getMockBuilder(TaskManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $client = $this->getMockBuilder(\GearmanClient::class)
            ->setMethods(['addTask', 'runTasks', 'setCompleteCallback'])
            ->getMock();

        $client->expects($this->any())
            ->method('setCompleteCallback')
            ->will($this->returnValue(true));

        $client->expects($this->any())
            ->method('addTask')
            ->will($this->returnValue(true));

        $client->expects($this->any())
            ->method('runTasks')
            ->will($this->returnValue(true));

        $tm->expects($this->any())
            ->method('getClient')
            ->will($this->returnValue($client));

        $logger = $this->getMockBuilder(Logger::class)
            ->setMethods(['log'])
            ->disableOriginalConstructor()
            ->getMock();

        $logger->expects($this->any())
            ->method('log')
            ->will($this->returnValue(true));

        $this->processor = new Processor($tm, $logger);
    }

    /**
     * @test
     * @dataProvider collectsDataProvider
     */
    public function runCollection($data)
    {
        $buckets = $this->builder->factory(json_decode($data, true));
        $completeTask = $this->getMockedGearmanTask($buckets);
        $expectedStartJobs = count($data[0]['collects']);

        foreach ($buckets as $bucket) {
            $this->processor->process($bucket);
            $runningJobs = Reflection::getPropertyOnObject(
                $this->processor,
                'runningJobs'
            );

            $this->processor->complete($completeTask);

            $this->assertEquals($expectedStartJobs, count($runningJobs));
            $this->assertFalse($this->processor->isProcessing());
        }
    }

    public function getMockedGearmanTask(
        CollectBucketCollection $buckets,
        ResultSet $resultSet = null
    ) {
        if (is_null($resultSet)) {
            /** @var Account $account */
            $account = $buckets->at(0)->getAccount();

            $resultSet = new ResultSet(
                $account,
                new JobCollection(),
                new MetricCollection(),
                new MetadataCollection()
            );
        }
        /** @var CollectBucket $bucket */
        $bucket = $buckets->at(0);
        /** @var Collect $collect */
        $collect = $bucket->getCollects()->at(0);

        $taskData = serialize($resultSet);
        $task = $this->getMockBuilder(\GearmanTask::class)
            ->setMethods(['data', 'unique'])
            ->getMock();

        $task->expects($this->any())
            ->method('data')
            ->will($this->returnValue($taskData));

        $task->expects($this->any())
            ->method('unique')
            ->will($this->returnValue(
                md5($collect->getId() . serialize($collect->getPendingJobs()->at(0)))
            ));

        return $task;
    }

    public function collectsDataProvider()
    {
        return [
            [
                '[
                        {
                            "time": "2017-10-05T19:45:13+00:00",
                            "account": {
                                "id": 1,
                                "email": "tester@fivemetrics.io",
                                "password": "$2y$13$5YzlzirzRy0u/Kta9xiMn.DjNnndIew28t5tJChEaMhTaUPlPpcJ.",
                                "roles": [
                                    "ROLE_API_V1",
                                    "ROLE_SYSTEM"
                                ],
                                "uid": "tester",
                                "username": "tester",
                                "dataSourceParameterValues": [],
                                "collects": []
                            },
                            "collects": [
                                {
                                    "dataSource": {
                                        "name": "aws.ec2",
                                        "maxConcurrency": 5,
                                        "collectInterval": 300
                                    },
                                    "parameters": [
                                        {
                                            "name": "aws.key",
                                            "value": "test-key"
                                        },
                                        {
                                            "name": "aws.secret",
                                            "value": "test-secret"
                                        },
                                        {
                                            "name": "aws.region",
                                            "value": "us-east-1"
                                        }
                                    ],
                                    "isEnabled": true,
                                    "lastUpdate": "2017-10-05T18:03:21+00:00",
                                    "id": 1
                                }
                            ]
                        }
                    ]'
             ]
        ];
    }
}
