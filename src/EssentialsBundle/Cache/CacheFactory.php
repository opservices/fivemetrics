<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 05/05/17
 * Time: 15:39
 */

namespace EssentialsBundle\Cache;

use Doctrine\Common\Cache\CacheProvider;
use EssentialsBundle\Entity\Account\AccountInterface;
use EssentialsBundle\KernelLoader;

/**
 * Class CacheFactory
 * @package EssentialsBundle\Cache
 */
class CacheFactory
{
    /**
     * @param AccountInterface $account
     * @param string $cacheProvider
     * @return CacheProvider
     */
    public function factory(
        AccountInterface $account,
        string $cacheProvider = 'local_cache'
    ): CacheProvider {
        $cache = KernelLoader::load()->getContainer()->get($cacheProvider);

        /** @var CacheProvider $cache */
        $cache->setNamespace($account->getUid());
        return $cache;
    }
}
