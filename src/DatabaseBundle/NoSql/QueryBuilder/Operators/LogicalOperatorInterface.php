<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 01/06/17
 * Time: 15:48
 */

namespace DatabaseBundle\NoSql\QueryBuilder\Operators;

use EssentialsBundle\Collection\TypedCollectionAbstract;

interface LogicalOperatorInterface
{
    public function setElements(TypedCollectionAbstract $elements);

    public function __toString(): string;
}
