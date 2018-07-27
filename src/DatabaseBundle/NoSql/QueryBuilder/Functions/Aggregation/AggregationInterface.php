<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 02/06/17
 * Time: 10:19
 */

namespace DatabaseBundle\NoSql\QueryBuilder\Functions\Aggregation;

use DatabaseBundle\NoSql\QueryBuilder\Column;

interface AggregationInterface
{
    public function __construct(Column $column);

    public function __toString(): string;
}
