<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 25/09/17
 * Time: 16:49
 */

namespace CollectorBundle\Tests\Collect;

use CollectorBundle\Collect\Collect;
use CollectorBundle\Collect\CollectBucket;
use CollectorBundle\Collect\CollectBucketBuilder;
use CollectorBundle\Collect\CollectBucketCollection;
use CollectorBundle\Job\JobBuilder;
use DataSourceBundle\Entity\Aws\Region\RegionProvider;
use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Entity\Account\AccountBuilder;
use EssentialsBundle\Entity\Builder\EntityBuilderProvider;
use PHPUnit\Framework\TestCase;

class CollectBucketBuilderTest extends TestCase
{
    /**
     * @var CollectBucketBuilder
     */
    protected $builder;

    public function setUp()
    {
        /** @var AccountBuilder $builder */
        $this->builder = new CollectBucketBuilder(
            EntityBuilderProvider::factory(Account::class),
            new JobBuilder(new RegionProvider())
        );
    }

    /**
     * @param array $data
     * @param bool $discovery
     * @test
     * @dataProvider collectsDataProvider
     */
    public function buildCollectBucket(array $data, bool $discovery)
    {
        $collection = $this->builder->factory($data, $discovery);

        $this->assertInstanceOf(CollectBucketCollection::class, $collection);
        $this->assertCount(count($data), $collection);

        foreach ($collection as $i => $bucket) {
            /** @var CollectBucket $bucket */
            $this->assertEquals($data[$i]['time'], $bucket->getTime());
            $this->assertEquals(
                $data[$i]['account']['email'],
                $bucket->getAccount()->getEmail()
            );

            $collects = $bucket->getCollects();
            $this->assertCount(count($data[$i]['collects']), $collects);

            foreach ($collects as $j => $collect) {
                /** @var Collect $collect */
                $this->assertCount(
                    count($data[$i]['collects'][$j]['parameters']),
                    $collect->getParameters()
                );

                if ($discovery) {
                    $this->assertStringStartsWith(
                        'discovery-',
                        $collect->getId()
                    );
                } else {
                    $this->assertEquals(
                        $data[$i]['collects'][$j]['id'],
                        $collect->getId()
                    );
                }
            }
        }
    }

    public function collectsDataProvider()
    {
        return [
            [
                json_decode('[{
                    "time": "2017-09-25T19:34:06+00:00",
                    "account": {
                        "id": 1,
                        "email": "tester@fivemetrics.io",
                        "password": "$2y$13$5YzlzirzRy0u/Kta9xiMn.DjNnndIew28t5tJChEaMhTaUPlPpcJ.",
                        "roles": [
                            "ROLE_API_V1",
                            "ROLE_SYSTEM"
                        ],
                        "uid": "tester",
                        "username": "tester"
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
                                    "value": "key-test"
                                },
                                {
                                    "name": "aws.secret",
                                    "value": "secret-test"
                                },
                                {
                                    "name": "aws.region",
                                    "value": "us-east-1"
                                }
                            ],
                            "isEnabled": true,
                            "lastUpdate": null,
                            "id": 1
                        }
                    ]
                }]', true),
                false
            ],
            [
                json_decode('[{
                    "time": "2017-09-25T19:34:06+00:00",
                    "account": {
                        "id": 1,
                        "email": "tester@fivemetrics.io",
                        "password": "$2y$13$5YzlzirzRy0u/Kta9xiMn.DjNnndIew28t5tJChEaMhTaUPlPpcJ.",
                        "roles": [
                            "ROLE_API_V1",
                            "ROLE_SYSTEM"
                        ],
                        "uid": "tester",
                        "username": "tester"
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
                                    "value": "key-test"
                                },
                                {
                                    "name": "aws.secret",
                                    "value": "secret-test"
                                },
                                {
                                    "name": "aws.region",
                                    "value": "us-east-1"
                                }
                            ],
                            "isEnabled": true,
                            "lastUpdate": "2017-09-25T19:34:06+00:00",
                            "id": 1
                        }
                    ]
                }]', true),
                false
            ],
            [
                json_decode('[{
                    "time": "2017-09-25T19:34:06+00:00",
                    "account": {
                        "id": 1,
                        "email": "tester@fivemetrics.io",
                        "password": "$2y$13$5YzlzirzRy0u/Kta9xiMn.DjNnndIew28t5tJChEaMhTaUPlPpcJ.",
                        "roles": [
                            "ROLE_API_V1",
                            "ROLE_SYSTEM"
                        ],
                        "uid": "tester",
                        "username": "tester"
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
                                    "value": "key-test"
                                },
                                {
                                    "name": "aws.secret",
                                    "value": "secret-test"
                                },
                                {
                                    "name": "aws.region",
                                    "value": "us-east-1"
                                }
                            ],
                            "isEnabled": true,
                            "lastUpdate": "2017-09-25T19:34:06+00:00",
                            "id": "discovery-id"
                        }
                    ]
                }]', true),
                true
            ]
        ];
    }
}
