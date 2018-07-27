<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 05/05/17
 * Time: 17:46
 */

namespace DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws;

use Doctrine\Common\Cache\CacheProvider;

/**
 * Class DataLoaderAbstract
 * @package DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws
 */
abstract class DataLoaderAbstract
{
    /**
     * @var JobAbstract
     */
    protected $job;

    /**
     * @var CacheProvider
     */
    protected $cacheProvider;

    /**
     * DataLoader constructor.
     * @param JobAbstract $job
     * @param CacheProvider|null $cacheProvider
     */
    public function __construct(
        JobAbstract $job,
        CacheProvider $cacheProvider = null
    ) {
        $this->job = $job;
        $this->cacheProvider = $cacheProvider;
    }

    /**
     * @return JobAbstract
     */
    public function getJob(): JobAbstract
    {
        return $this->job;
    }

    /**
     * @param string $key
     * @param $data
     * @param int $lifetime
     * @return DataLoaderAbstract
     */
    protected function cacheWrite(string $key, $data, int $lifetime = 60): DataLoaderAbstract
    {
        if (is_null($this->cacheProvider)) {
            return $this;
        }

        $this->cacheProvider->save($key, serialize($data), $lifetime);

        return $this;
    }

    /**
     * @param string $key
     * @return mixed
     */
    protected function cacheFetch(string $key)
    {
        if (is_null($this->cacheProvider)) {
            return false;
        }

        return unserialize($this->cacheProvider->fetch($key));
    }
}
