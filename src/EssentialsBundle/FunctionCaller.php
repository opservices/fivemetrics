<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 23/02/17
 * Time: 09:39
 */

namespace EssentialsBundle;

/**
 * Class FunctionCaller
 * This class is only for call function
 * @package EssentialsBundle
 */
class FunctionCaller
{
    /**
     * @param $fn
     * @param array $args
     * @return mixed
     */
    public function call($fn, array $args = [])
    {
        return call_user_func_array($fn, $args);
    }

    /**
     * @param $method
     * @param $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        return call_user_func_array($method, $args);
    }
}
