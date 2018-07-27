<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 31/05/17
 * Time: 12:40
 */

namespace DatabaseBundle\NoSql\QueryBuilder;

/**
 * Interface QueryElementInterface
 * @package DatabaseBundle\NoSql\QueryBuilder
 */
interface QueryElementInterface
{
    public function __toString(): string;
}
