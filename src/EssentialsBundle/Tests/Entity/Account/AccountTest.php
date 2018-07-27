<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 14/07/17
 * Time: 09:28
 */

namespace EssentialsBundle\Tests\Entity\Account;

use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Entity\Builder\EntityBuilderProvider;
use EssentialsBundle\Entity\DateTime\DateTime;
use PHPUnit\Framework\TestCase;

class AccountTest extends TestCase
{
    /**
     * @var Account
     */
    private $account;

    public function setUp()
    {
        $this->account = new Account;
    }
    /**
     * @test
     */
    public function getAccountProperties()
    {
        /** @var Account $account */
        $account = EntityBuilderProvider::factory(Account::class)
            ->factory([
                'id'       => 1,
                'email'    => 'tester@fivemetrics.io',
                'username' => 'tester',
                'uid'      => 'tester',
                'roles'    => [ 'ROLE_TEST' ],
                'password' => 'fakeHash',
                'plainPassword' => 'fakePlainPassword'
            ]);

        $this->assertEquals(1, $account->getId());
        $this->assertEquals('tester@fivemetrics.io', $account->getEmail());
        $this->assertEquals('tester', $account->getUsername());
        $this->assertEquals('tester', $account->getUid());
        $this->assertEquals([ 'ROLE_USER', 'ROLE_TEST' ], $account->getRoles());
        $this->assertEquals('fakeHash', $account->getPassword());
        $this->assertEquals('fakePlainPassword', $account->getPlainPassword());
        $this->assertNull($account->getOnboardingDoneAt());
    }

    /**
     * @test
     */
    public function setUsername()
    {
        $this->account->setUsername('tester');
        $this->assertEquals('tester', $this->account->getUsername());
    }

    /**
     * @test
     */
    public function getUsernameFromAccountWithoutUsername()
    {
        $this->account->setEmail('tester@fivemetrics.io');
        $this->assertEquals('tester@fivemetrics.io', $this->account->getUsername());
    }

    /**
     * @test
     */
    public function getAccountUserRole()
    {
        $this->account->setRoles([ 'ROLE_USER' ]);
        $this->assertEquals([ 'ROLE_USER' ], $this->account->getRoles());
    }

    /**
     * @test
     */
    public function setIndexedRoles()
    {
        $this->account->setRoles([
            '0' => 'ROLE_USER',
            '2' => 'ROLE_TEST',
        ]);

        $this->assertEquals([ 'ROLE_USER', 'ROLE_TEST' ], $this->account->getRoles());
    }

    /**
     * @test
     */
    public function getPassword()
    {
        $this->account->setPassword('pass');
        $this->assertEquals('pass', $this->account->getPassword());
    }

    /**
     * @test
     */
    public function eraseCredentials()
    {
        $this->account->setPlainPassword('pass');
        $this->account->eraseCredentials();
        $this->assertNull($this->account->getPlainPassword());
    }

    /**
     * @test
     */
    public function setOnboardingDoneAt()
    {
        $dt = new DateTime('now', new \DateTimeZone('UTC'));
        $this->account->setOnboardingDoneAt($dt);
        $this->assertEquals($dt, $this->account->getOnboardingDoneAt());
    }

    /**
     * @test
     */
    public function generateApiKey()
    {
        $expected = 40;
        $actual = strlen($this->account->generateApiKey());
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function hashCodeBySalt()
    {
        $expected = 32;
        $hash = $this->account->hashCodeBySalt();
        $actual = strlen($hash);
        $this->assertEquals($expected, $actual);
        $this->assertEquals($this->account->hashCodeBySalt(), $hash);
    }

    /**
     *  @testdox Should add a role for a specific account
     */
    public function addRole()
    {
        $this->assertEquals(['ROLE_USER'], $this->account->getRoles());
        $this->account->addRole('ROLE_ADDED');
        $this->assertEquals(['ROLE_USER', 'ROLE_ADDED'], $this->account->getRoles());
    }

    /**
     * @testdox Should guarantee that a role won't be inserted more than once
     */
    public function addDuplicatedRole()
    {
        $this->account->addRole('ROLE_ADDED');
        $this->account->addRole('ROLE_ADDED');
        $this->assertEquals(['ROLE_USER', 'ROLE_ADDED'], $this->account->getRoles());
    }

    /**
     * @testdox Should remove a role from a specific account
     */
    public function removeRole()
    {
        $this->account->addRole('ROLE_ADDED');
        $this->account->addRole('ROLE_ADDED2');

        $this->assertTrue($this->account->removeRole('ROLE_ADDED'));
        $this->assertEquals(['ROLE_USER', 'ROLE_ADDED2'], $this->account->getRoles());

        $this->assertFalse($this->account->removeRole('ROLE_ADDED'));
    }

    /**
     * @testdox Should return if an account has a role
     */
    public function hasRole()
    {
        $this->account->addRole('ROLE_TRUE');
        $this->assertTrue($this->account->hasRole('ROLE_TRUE'));
        $this->assertFalse($this->account->hasRole('ROLE_FALSE'));
    }
}
