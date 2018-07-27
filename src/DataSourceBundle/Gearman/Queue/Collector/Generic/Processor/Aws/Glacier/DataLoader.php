<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 7/27/17
 * Time: 12:03 PM
 */

namespace DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier;

use DataSourceBundle\Aws\Glacier\Glacier as GlacierClient;
use DataSourceBundle\Collection\Aws\Glacier\Job\JobCollection;
use DataSourceBundle\Collection\Aws\Glacier\Vault\VaultCollection;
use DataSourceBundle\Entity\Aws\Glacier\Vault\Vault;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\DataLoaderAbstract;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\JobAbstract;
use Doctrine\Common\Cache\CacheProvider;

/**
 * Class DataLoader
 * @package DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier
 */
class DataLoader extends DataLoaderAbstract
{

    /**
     * @var $client GlacierClient
     */
    protected $client;

    /**
     * DataLoader constructor.
     * @param JobAbstract $job
     * @param CacheProvider|null $cacheProvider
     * @param GlacierClient|null $client
     */
    public function __construct(
        JobAbstract $job,
        CacheProvider $cacheProvider = null,
        GlacierClient $client = null
    ) {
        parent::__construct($job, $cacheProvider);
        $this->client = $client;
    }


    public function __destruct()
    {
        $this->client = null;
    }

    /**
     * @return GlacierClient
     */
    protected function getGlacierClient()
    {
        if (is_null($this->client)) {
            $this->client = new GlacierClient(
                $this->getJob()->getKey(),
                $this->getJob()->getSecret(),
                $this->getJob()->getRegion()
            );
        }
        return $this->client;
    }

    /**
     * @return VaultCollection
     */
    public function retrieveVaults(): VaultCollection
    {
        $key = $this->getJob()->getRegion()->getCode()
            . __CLASS__ . __METHOD__;

        $data = $this->cacheFetch($key);

        if ($data) {
            return $data;
        }

        $data = $this->getGlacierClient()
            ->retrieveVaults();

        $this->cacheWrite($key, $data);

        return $data;
    }

    /**
     * @param Vault $vault
     * @return JobCollection
     */
    public function getJobsByVault(Vault $vault): JobCollection
    {
        $key = $this->getJob()->getRegion()->getCode()
            . __CLASS__ . __METHOD__;

        $data = $this->cacheFetch($key);

        if ($data) {
            return $data;
        }

        $data = $this->getGlacierClient()->retrieveJobs($vault);
        $this->cacheWrite($key, $data);
        return $data;
    }
}
