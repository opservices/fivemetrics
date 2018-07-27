<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 7/25/17
 * Time: 10:29 AM
 */

namespace DataSourceBundle\Entity\Aws\Glacier\Vault;

use DataSourceBundle\Collection\Aws\Glacier\Vault\VaultCollection;
use DataSourceBundle\Entity\Aws\Tag\Builder as TagBuilder;

/**
 * Class Builder
 * @package DataSourceBundle\Entity\Aws\Glacier\Vault
 */
class Builder
{
    /**
     * @param array $data
     * @param VaultCollection|null $vaults
     * @return VaultCollection
     */
    public static function build(
        array $data,
        VaultCollection $vaults = null
    ): VaultCollection {

        if (is_null($vaults)) {
            $vaults = new VaultCollection();
        }

        foreach ($data as $vault) {
            $vaults->add(
                new Vault(
                    $vault["VaultName"],
                    (empty($vault["NumberOfArchives"])) ? 0 : $vault["NumberOfArchives"],
                    (empty($vault["SizeInBytes"])) ? 0 : $vault["SizeInBytes"],
                    (empty($vault["Tags"])) ? null : TagBuilder::build($vault["Tags"])
                )
            );
        }

        return $vaults;
    }
}
