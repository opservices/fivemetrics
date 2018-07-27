<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 23/11/17
 * Time: 08:27
 */

namespace EssentialsBundle\Tests\Entity\Account;

use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Entity\Account\AccountConfiguration;
use EssentialsBundle\Entity\Builder\EntityBuilderProvider;
use PHPUnit\Framework\TestCase;

class AccountConfigurationTest extends TestCase
{
    /**
     * @test
     */
    public function getProperties()
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

        $this->assertSame($account, $configuration->getAccount());
        $this->assertEquals(1, $configuration->getId());
        $this->assertEquals('test', $configuration->getName());
        $this->assertEquals('unit', $configuration->getValue());
    }
}
