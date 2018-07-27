<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 7/24/17
 * Time: 2:10 PM
 */

namespace DataSourceBundle\Collection\Aws\Glacier\Vault;

use DataSourceBundle\Entity\Aws\Glacier\Vault\Vault;
use EssentialsBundle\Collection\TypedCollectionAbstract;

class VaultCollection extends TypedCollectionAbstract
{

    /**
     * @return string
     */
    public function getClass(): string
    {
        return Vault::class;
    }

    /**
     * @param null $added
     * @param null $removed
     * @return mixed
     */
    protected function onChanged($added = null, $removed = null)
    {
    }
}
