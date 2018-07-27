<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 31/05/17
 * Time: 12:55
 */

namespace DatabaseBundle\Collection\NoSql\QueryBuilder;

use DatabaseBundle\NoSql\QueryBuilder\QueryElementInterface;
use EssentialsBundle\Collection\TypedCollectionAbstract;

/**
 * Class ColumnsCollection
 * @package DatabaseBundle\Collection\NoSql\QueryBuilder
 */
class ColumnCollection extends TypedCollectionAbstract implements QueryElementInterface
{
    public function getClass(): string
    {
        return "DatabaseBundle\NoSql\QueryBuilder\Column";
    }

    protected function onChanged($added = null, $removed = null)
    {
    }

    public function __toString(): string
    {
        return implode(', ', $this->elements);
    }
}
