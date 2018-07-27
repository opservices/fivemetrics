<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 8/3/17
 * Time: 3:19 PM
 */

namespace DataSourceBundle\Tests\Collection\Aws\Glacier;

use DataSourceBundle\Collection\Aws\Glacier\Vault\VaultCollection;
use DataSourceBundle\Entity\Aws\Glacier\Vault\Vault;
use PHPUnit\Framework\TestCase;

/**
 * Class VaultCollectionTest
 * @package DataSourceBundle\Tests\Collection\Aws\Glacier
 */
class VaultCollectionTest extends TestCase
{
    /**
     * @var VaultCollection
     */
    protected $vaultCollection;

    public function setUp()
    {
        $this->vaultCollection = new VaultCollection();
    }

    /**
     * @test
     */
    public function addBucket()
    {
        $this->vaultCollection->add(new Vault('teste'));

        $this->assertEquals(
            1,
            count($this->vaultCollection)
        );
    }
}
