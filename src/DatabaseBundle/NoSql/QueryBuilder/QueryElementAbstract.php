<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 31/05/17
 * Time: 13:15
 */

namespace DatabaseBundle\NoSql\QueryBuilder;

/**
 * Class QueryElementAbstract
 * @package DatabaseBundle\NoSql\QueryBuilder
 */
abstract class QueryElementAbstract implements QueryElementInterface
{
    protected function getElementName(): string
    {
        $class = get_class($this);

        return strtoupper(
            substr_replace(
                $class,
                "",
                0,
                strrpos($class, "\\") + 1
            )
        );
    }
}
