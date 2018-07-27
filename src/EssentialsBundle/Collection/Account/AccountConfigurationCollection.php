<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 01/11/17
 * Time: 11:07
 */

namespace EssentialsBundle\Collection\Account;

use EssentialsBundle\Collection\TypedCollectionAbstract;
use EssentialsBundle\Entity\Account\AccountConfiguration;

class AccountConfigurationCollection extends TypedCollectionAbstract
{
    /**
     * @inheritDoc
     */
    public function getClass(): string
    {
        return AccountConfiguration::class;
    }

    /**
     * @inheritDoc
     */
    protected function onChanged($added = null, $removed = null)
    {
    }
}
