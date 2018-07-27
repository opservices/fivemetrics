<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 23/11/17
 * Time: 08:51
 */

namespace EssentialsBundle\Tests\Collection\Account;

use EssentialsBundle\Collection\Account\AccountConfigurationCollection;
use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Entity\Account\AccountConfiguration;
use EssentialsBundle\Entity\Builder\EntityBuilderProvider;
use PHPUnit\Framework\TestCase;

class AccountConfigurationCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function addElementConstructor()
    {
        $account = EntityBuilderProvider::factory(Account::class)
            ->factory([
                'email' => 'tester@fivemetrics.io',
                'uid' => 'tester',
                'id' => 1,
                'password' => 'lshglisgofuiswfui',
                'roles' => [ 'ROLE_USER' ],
            ]);

        /** @var AccountConfiguration $configuration */
        $configuration = EntityBuilderProvider::factory(AccountConfiguration::class)
            ->factory([
                'id' => 1,
                'account' => $account,
                'name' => 'test',
                'value' => 'unit'
            ]);

        $collection = new AccountConfigurationCollection([ $configuration ]);

        $this->assertSame($configuration, $collection->at(0));
    }
}
