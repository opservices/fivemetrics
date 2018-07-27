<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 23/06/17
 * Time: 14:45
 */

namespace EssentialsBundle\Entity\Account;

use EssentialsBundle\Entity\Builder\EntityBuilderAbstract;

/**
 * Class AccountBuilder
 * @package EssentialsBundle\Entity\Account
 */
class AccountConfigurationBuilder extends EntityBuilderAbstract
{
    /**
     * @param array $data
     * @return Account
     */
    public function factory(
        array $data,
        array $validationGroups = [ 'Default' ]
    ): AccountConfiguration {
        $configuration = $this->getInstance(AccountConfiguration::class, $data);

        if (! empty($validationGroups)) {
            $this->validate($configuration, $validationGroups);
        }

        return $configuration;
    }
}
