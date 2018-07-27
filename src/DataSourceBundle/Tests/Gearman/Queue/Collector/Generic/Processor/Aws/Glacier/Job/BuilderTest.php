<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 8/7/17
 * Time: 3:26 PM
 */

namespace DataSourceBundle\Tests\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier\Job;

use DataSourceBundle\Collection\Aws\Glacier\Vault\VaultCollection;
use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier\FilterCollection;
use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\JobCollection;
use DataSourceBundle\Entity\Aws\Glacier\Region\RegionProvider;
use DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier\Filter;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier\Job\Builder;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier\Job\Job;
use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Entity\DateTime\DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Class BuilderTest
 * @package DataSourceBundle\Tests\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier\Job
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
    public function buildJobWithOutData()
    {
        $job = $this->getJobTest();

        $filters = new FilterCollection([
            new Filter(
                'Glacier',
                [
                    'Job'
                ],
                $this->getVaultsTest()->at(0)
            )
        ]);

        $dataLoader = $this->getMockBuilder(
            'DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier\DataLoader'
        )->disableOriginalConstructor()
            ->setMethods([ 'retrieveVaults' ])
            ->getMock();

        $dataLoader->expects($this->once())
            ->method('retrieveVaults')
            ->willReturn($this->getVaultsTest());

        $jobs = $this->builder->build($dataLoader, $job, $filters);

        $filters->at(0)->setNamespace('Glacier\Job');

        $resultJob = $this->getJobTest()
            ->setData($filters);

        $resultJob->setDateTime($jobs->at(0)->getDateTime());
        $expectedResult = new JobCollection([$resultJob]);

        $this->assertEquals(
            $expectedResult,
            $jobs
        );
    }

    /**
     * @test
     */
    public function buildJobWithData()
    {
        $filter = new Filter(
            'Glacier',
            [
                'Job'
            ],
            $this->getVaultsTest()->at(0)
        );

        $filters = new FilterCollection([
            $filter
        ]);

        $job = $this->getJobTest()->setData($filter);

        $dataLoader = $this->getMockBuilder(
            'DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier\DataLoader'
        )->disableOriginalConstructor()
            ->getMock();

        $jobs = $this->builder->build($dataLoader, $job, $filters);

        $this->assertEquals(
            $job,
            $jobs
        );
    }

    public function getJobTest(): Job
    {
        $account = new Account();
        $account->setUid('unitTest');

        return new Job(
            $account,
            new DateTime(),
            RegionProvider::factory('us-east-1'),
            'test',
            'unit'
        );
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
            ],
        ];
        return \DataSourceBundle\Entity\Aws\Glacier\Vault\Builder::build($data);
    }
}
