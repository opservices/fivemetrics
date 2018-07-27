<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 26/09/17
 * Time: 13:39
 */

namespace CollectorBundle\Tests\Job;

use CollectorBundle\Collect\Collect;
use CollectorBundle\Collect\DataSource;
use CollectorBundle\Collect\Parameter;
use CollectorBundle\Collect\ParameterCollection;
use CollectorBundle\Job\JobBuilder;
use DataSourceBundle\Entity\Aws\Region\RegionProvider;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\JobAbstract;
use EssentialsBundle\Collection\Tag\TagCollection;
use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Entity\Builder\EntityBuilderProvider;
use EssentialsBundle\Entity\DateTime\DateTime;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EC2\Job\Job as EC2Job;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EBS\Job\Job as EBSJob;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer\Job\Job as ELBJob;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScaling\Job\Job as AutoScalingJob;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\S3\Job\Job as S3Job;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier\Job\Job as GlacierJob;
use EssentialsBundle\Entity\Tag\Tag;
use EssentialsBundle\Profiler\Profiler;
use PHPUnit\Framework\TestCase;

class JobBuilderTest extends TestCase
{
    /**
     * @var JobBuilder
     */
    protected $builder;

    public function setUp()
    {
        $this->builder = new JobBuilder(new RegionProvider());
    }

    /**
     * @test
     * @dataProvider awsCollectInstanceProvider
     */
    public function buildAwsJob(
        Collect $collect,
        string $expectedJobType,
        Profiler $profiler = null
    ) {
        $account = $this->getAccountInstance();
        /** @var JobAbstract $job */
        $job = $this->builder->factory(
            $account,
            new DateTime(),
            $collect,
            $profiler
        );

        $this->assertInstanceOf($expectedJobType, $job);

        /** @var Parameter $param */
        $param = $collect->getParameters()->find('aws.key');
        $this->assertEquals($param->getValue(), $job->getKey());

        $param = $collect->getParameters()->find('aws.secret');
        $this->assertEquals($param->getValue(), $job->getSecret());

        $param = $collect->getParameters()->find('aws.region');
        $this->assertEquals($param->getValue(), $job->getRegion()->getCode());

        if ($profiler) {
            $this->assertEquals(
                $param->getValue(),
                $profiler->getTags()->find('aws.region')->getValue()
            );

            $this->assertEquals(
                $account->getUid(),
                $profiler->getTags()->find('account')->getValue()
            );

            $this->assertEquals(
                $collect->getDataSource()->getName(),
                $profiler->getTags()->find('dataSource')->getValue()
            );
        }
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @dataProvider invalidAwsCollectInstanceProvider
     */
    public function buildInvalidCollect(Collect $collect)
    {
        $this->builder->factory(
            $this->getAccountInstance(),
            new DateTime(),
            $collect
        );
    }

    public function invalidAwsCollectInstanceProvider()
    {
        return [
            [
                new Collect(
                    1,
                    new DataSource(
                        'aws.invalid',
                        1,
                        300
                    ),
                    new ParameterCollection([
                        new Parameter(
                            'aws.region',
                            'us-east-1'
                        ),
                        new Parameter(
                            'aws.key',
                            'test'
                        ),
                        new Parameter(
                            'aws.secret',
                            'unit'
                        ),
                    ]),
                    false
                )
            ],
            [
                new Collect(
                    1,
                    new DataSource(
                        'aws.ec2',
                        1,
                        300
                    ),
                    new ParameterCollection([
                        new Parameter(
                            'aws.key',
                            'test'
                        ),
                        new Parameter(
                            'aws.secret',
                            'unit'
                        ),
                    ]),
                    false
                )
            ],
        ];
    }

    public function awsCollectInstanceProvider()
    {
        return [
            [
                new Collect(
                    1,
                    new DataSource(
                        'aws.ec2',
                        1,
                        300
                    ),
                    new ParameterCollection([
                        new Parameter(
                            'aws.region',
                            'us-east-1'
                        ),
                        new Parameter(
                            'aws.key',
                            'test'
                        ),
                        new Parameter(
                            'aws.secret',
                            'unit'
                        ),
                    ]),
                    false
                ),
                EC2Job::class,
                new Profiler(new TagCollection([
                    new Tag('origin', 'unit.test'),
                    new Tag('account', 'test'),
                    new Tag('dataSource', 'aws.ec2'),
                    new Tag('aws.region', 'us-east-1'),
                ]), false)
            ],
            [
                new Collect(
                    1,
                    new DataSource(
                        'aws.ebs',
                        1,
                        300
                    ),
                    new ParameterCollection([
                        new Parameter(
                            'aws.region',
                            'us-east-1'
                        ),
                        new Parameter(
                            'aws.key',
                            'test1'
                        ),
                        new Parameter(
                            'aws.secret',
                            'unit1'
                        ),
                    ]),
                    false
                ),
                EBSJob::class,
                new Profiler(new TagCollection([
                    new Tag('origin', 'unit.test'),
                    new Tag('account', 'test'),
                    new Tag('dataSource', 'aws.ebs'),
                    new Tag('aws.region', 'us-east-1'),
                ]), false)
            ],
            [
                new Collect(
                    1,
                    new DataSource(
                        'aws.elb',
                        1,
                        300
                    ),
                    new ParameterCollection([
                        new Parameter(
                            'aws.region',
                            'us-east-1'
                        ),
                        new Parameter(
                            'aws.key',
                            'test2'
                        ),
                        new Parameter(
                            'aws.secret',
                            'unit2'
                        ),
                    ]),
                    false
                ),
                ELBJob::class
            ],
            [
                new Collect(
                    1,
                    new DataSource(
                        'aws.autoscaling',
                        1,
                        300
                    ),
                    new ParameterCollection([
                        new Parameter(
                            'aws.region',
                            'us-east-1'
                        ),
                        new Parameter(
                            'aws.key',
                            'test'
                        ),
                        new Parameter(
                            'aws.secret',
                            'unit'
                        ),
                    ]),
                    false
                ),
                AutoScalingJob::class
            ],
            [
                new Collect(
                    1,
                    new DataSource(
                        'aws.s3',
                        1,
                        300
                    ),
                    new ParameterCollection([
                        new Parameter(
                            'aws.region',
                            'us-east-1'
                        ),
                        new Parameter(
                            'aws.key',
                            'test'
                        ),
                        new Parameter(
                            'aws.secret',
                            'unit'
                        ),
                    ]),
                    false
                ),
                S3Job::class
            ],
            [
                new Collect(
                    1,
                    new DataSource(
                        'aws.glacier',
                        1,
                        300
                    ),
                    new ParameterCollection([
                        new Parameter(
                            'aws.region',
                            'us-east-1'
                        ),
                        new Parameter(
                            'aws.key',
                            'test'
                        ),
                        new Parameter(
                            'aws.secret',
                            'unit'
                        ),
                    ]),
                    false
                ),
                GlacierJob::class
            ],
        ];
    }

    protected function getAccountInstance(): Account
    {
        return EntityBuilderProvider::factory(Account::class)
            ->factory(
                [
                    'id' => 1,
                    'email' => 'test@test.com',
                    'uid' => 'test'
                ],
                []
            );
    }
}
