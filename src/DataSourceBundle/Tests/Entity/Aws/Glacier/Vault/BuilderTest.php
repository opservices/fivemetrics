<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 8/4/17
 * Time: 3:46 PM
 */

namespace DataSourceBundle\Tests\Entity\Aws\Glacier\Vault;

use DataSourceBundle\Entity\Aws\Glacier\Vault\Builder;
use PHPUnit\Framework\TestCase;

/**
 * Class BuilderTest
 * @package DataSourceBundle\Tests\Entity\Aws\Glacier\Vault
 */
class BuilderTest extends TestCase
{

    /**
     * @test
     * @dataProvider getDataValidToVaultProvider
     */
    public function vaultTest($data)
    {
        $vault = Builder::build([$data]);
        $this->assertEquals('newVault', $vault->current()->getVaultName());
        $vault->current()->setVaultName("vaultNew");
        $this->assertEquals('vaultNew', $vault->current()->getVaultName());
        $this->assertEquals(1024, $vault->current()->getSizeInBytes());
        $this->assertEquals(1, $vault->current()->getNumberOfArchives());
        $this->assertGreaterThan(0, count($vault->current()->getTags()));
    }

    public function getDataValidToVaultProvider()
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
        return [$data];
    }
}
