<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 31/05/17
 * Time: 12:28
 */

namespace DatabaseBundle\NoSql\QueryBuilder\Functions\Aggregation;

use DatabaseBundle\NoSql\QueryBuilder\Column;

/**
 * Class AggregationAbstract
 * @package DatabaseBundle\NoSql\QueryBuilder\Functions\Aggregation
 */
abstract class AggregationAbstract extends Column implements AggregationInterface
{
    /**
     * @var Column
     */
    protected $column;

    /**
     * Aggregation constructor.
     * @param string $method
     */
    public function __construct(Column $column)
    {
        $this->column = $column;
    }

    public function __toString(): string
    {
        return $this->getElementName() . '(' . $this->column . ') AS "value"';
    }
}
