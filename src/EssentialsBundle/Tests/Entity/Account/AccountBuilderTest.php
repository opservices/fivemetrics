<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 14/07/17
 * Time: 09:50
 */

namespace EssentialsBundle\Tests\Entity\Account;

use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Entity\Builder\EntityBuilderProvider;
use PHPUnit\Framework\TestCase;

/**
 * Class AccountBuilderTest
 * @package EssentialsBundle\Tests\Entity\Account
 */
class AccountBuilderTest extends TestCase
{
    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function buildAccountWithoutEmail()
    {
        EntityBuilderProvider::factory(Account::class)
            ->factory([]);
    }
}
