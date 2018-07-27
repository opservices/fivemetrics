<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 7/6/17
 * Time: 11:24 AM
 */

namespace EssentialsBundle\MutexProvider;

use malkusch\lock\mutex\PredisMutex;
use Predis\Client;

/**
 * Class MutexProvider
 * @package EssentialsBundle\Mutex
 */
class MutexProvider
{
    /**
     * @var
     */
    protected $cache;

    /**
     * @var string
     */
    protected $host;

    /**
     * @var int
     */
    protected $port;

    /**
     * MutexProvider constructor.
     * @param string $host
     * @param string $port
     */
    public function __construct(string $host, string $port)
    {
        $this->host = $host;
        $this->port = (int)$port;
    }

    /**
     * @param string $lockName
     * @return PredisMutex
     */
    public function getMutexInstance(string $lockName): PredisMutex
    {
        if (! $this->cache[$lockName]) {
            $this->cache[$lockName] = new PredisMutex([
                new Client([
                    'scheme' => 'tcp',
                    'host' => $this->host,
                    'port' => $this->port,
                ])
            ], $lockName);
        }

        return $this->cache[$lockName];
    }
}
