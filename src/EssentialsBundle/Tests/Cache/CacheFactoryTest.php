<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 14/07/17
 * Time: 09:08
 */

namespace EssentialsBundle\Tests\Cache;

use Doctrine\Common\Cache\CacheProvider;
use EssentialsBundle\Cache\CacheFactory;
use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Entity\Builder\EntityBuilderProvider;
use PHPUnit\Framework\TestCase;

class CacheFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function getCacheProviderInstance()
    {
        $account = EntityBuilderProvider::factory(Account::class)
            ->factory([
                'id'    => 1,
                'email' => 'tester@fivemetrics.io',
                'uid'   => 'tester',
            ]);

        $cache = (new CacheFactory)->factory($account, 'local_cache');

        $this->assertInstanceOf(CacheProvider::class, $cache);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function getInvalidCacheProvider()
    {
        $account = EntityBuilderProvider::factory(Account::class)
            ->factory([
                'id'    => 1,
                'email' => 'tester@fivemetrics.io',
                'uid'   => 'tester',
            ]);

        (new CacheFactory)->factory($account, 'fake_cache');
    }
}
