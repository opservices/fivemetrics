<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 8/8/17
 * Time: 10:20 AM
 */

namespace DataSourceBundle\Tests\Entity\Aws\Glacier\Job;

use DataSourceBundle\Collection\Aws\Glacier\Vault\VaultCollection;
use DataSourceBundle\Entity\Aws\Glacier\Job\Builder;
use DataSourceBundle\Entity\Aws\Glacier\Job\Job;
use DataSourceBundle\Entity\Aws\Glacier\Vault\Builder as VaultBuilder;
use EssentialsBundle\Entity\DateTime\DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Class BuilderTest
 * @package DataSourceBundle\Tests\Entity\Aws\Glacier\Job
 */
class BuilderTest extends TestCase
{

    /**
     * @test
     */
    public function vaultJobTest()
    {
        /**
         * @var $job Job
         */
        $job = $this->getVaultJobTest()->at(0);
        $job->setVault($this->getVaultsTest()->at(0));
        $job->setJobId("newId");
        $job->setVaultARN("arn:aws:glacier:us-east-1:vaults/vaultfivemetrics");
        $job->setAction('InventoryRetrieval');
        $job->setStatusCode('Succeeded');
        $job->setCompleted(true);
        $job->setCreationDate(new DateTime());
        $this->assertInstanceOf(DateTime::class, $job->getCreationDate());
        $this->assertTrue($job->isCompleted());
        $this->assertEquals('newId', $job->getJobId());
        $this->assertEquals('arn:aws:glacier:us-east-1:vaults/vaultfivemetrics', $job->getVaultARN());
        $this->assertEquals('Succeeded', $job->getStatusCode());
        $this->assertEquals('InventoryRetrieval', $job->getAction());
        $this->assertEquals($this->getVaultsTest()->at(0), $job->getVault());
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function setInvalidVaultId()
    {
        /**
         * @var $job Job
         */
        $job = $this->getVaultJobTest()->at(0);
        $job->setJobId(null);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function setInvalidVaultStatusCode()
    {
        /**
         * @var $job Job
         */
        $job = $this->getVaultJobTest()->at(0);
        $job->setStatusCode('pineapple');
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
            ]
        ];

        return VaultBuilder::build($data);
    }

    public function getVaultJobTest()
    {
        $vault = $this->getVaultsTest()->at(0);
        $jobs = [];
        $jobs[0]["JobId"] = "id";
        $jobs[0]["Action"] = 'InventoryRetrieval';
        $jobs[0]["VaultARN"] = 'arn:aws:glacier:us-east-1:239620292590:vaults/vaultfivemetrics';
        $jobs[0]["CreationDate"] = "2017-08-07T15:47:30.119Z";
        $jobs[0]["Completed"] = true;
        $jobs[0]["StatusCode"] = "Succeeded";
        $jobs[0]["CompletionDate"] = "2017-08-07T15:47:30.119Z";
        return Builder::build($jobs, $vault);
    }
}
