<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 01/03/17
 * Time: 18:08
 */

namespace GearmanBundle\Configuration;

/**
 * Class Loader
 * @package GearmanBundle\Configuration
 */
class Loader
{
    protected static $loader = null;

    /**
     * @codeCoverageIgnore
     */
    protected function __construct()
    {
    }

    /**
     * @return LoaderInterface
     */
    public static function getInstance(): LoaderInterface
    {
        if (is_null(self::$loader)) {
            self::$loader = new DefaultLoader();
        }

        return self::$loader;
    }
}
