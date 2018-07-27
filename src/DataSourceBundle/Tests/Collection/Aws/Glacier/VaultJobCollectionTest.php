<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 8/4/17
 * Time: 1:51 PM
 */

namespace DataSourceBundle\Tests\Collection\Aws\Glacier;

use DataSourceBundle\Collection\Aws\Glacier\Job\JobCollection;
use DataSourceBundle\Entity\Aws\Glacier\Job\Job;
use DataSourceBundle\Entity\Aws\Glacier\Vault\Vault;
use EssentialsBundle\Entity\DateTime\DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Class VaultJobCollectionTest
 * @package DataSourceBundle\Tests\Collection\Aws\Glacier
 */
class VaultJobCollectionTest extends TestCase
{
    /**
     * @var JobCollection
     */
    protected $vaultJobCollection;

    public function setUp()
    {
        $this->vaultJobCollection = new JobCollection();
    }

    /**
     * @test
     */
    public function addJobToVaultCollection()
    {

        $this->vaultJobCollection->add(
            new Job(
                "qRXHGNajXdEG90Hy1v9RufErrrosGJqy61K79pVUF4eL-LquES6n1Sb7aWAmddEpv0uBfLZHCgPdP2cYwwFHF1mHT-UT",
                new Vault('vault'),
                'InventoryRetrieval',
                'arn:aws:glacier:us-east-1:239620292590:vaults/vaultfivemetrics',
                new DateTime(),
                false,
                "InProgress"
            )
        );

        $this->assertEquals(
            1,
            count($this->vaultJobCollection)
        );
    }
}
