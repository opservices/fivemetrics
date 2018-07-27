<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 08/05/17
 * Time: 08:45
 */

namespace EssentialsBundle;

use AppKernel;

/**
 * Class KernelLoader
 * @package EssentialsBundle
 */
class KernelLoader
{
    /**
     * @var AppKernel
     */
    protected static $kernel = null;

    /**
     * @param string $env
     * @param bool $debug
     * @return AppKernel
     */
    public static function load(string $env = 'prod', bool $debug = false): AppKernel
    {
        if (empty(self::$kernel)) {
            self::$kernel = self::getKernelInstance($env, $debug);
        }

        return self::$kernel;
    }

    /**
     * @param string $env
     * @param bool $debug
     * @return AppKernel
     */
    public static function reload(string $env = 'prod', bool $debug = false): AppKernel
    {
        if (! empty(self::$kernel)) {
            self::$kernel = null;
        }

        return self::load($env, $debug);
    }

    /**
     * @param string $env
     * @param bool $debug
     * @return AppKernel
     */
    protected static function getKernelInstance(string $env = 'prod', bool $debug = false): AppKernel
    {
        if (self::$kernel) {
            return self::$kernel;
        }

        global $kernel;

        if (empty($kernel)) {
            self::$kernel = new AppKernel($env, $debug);
            self::$kernel->boot();
        } else {
            self::$kernel = $kernel;
        }

        return self::$kernel;
    }
}
