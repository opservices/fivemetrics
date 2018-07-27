<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 01/06/17
 * Time: 15:15
 */

namespace DatabaseBundle\NoSql\QueryBuilder\Operators;

/**
 * Class AndOperator
 * @package DatabaseBundle\NoSql\QueryBuilder
 */
class LogicalAnd extends LogicalOperatorAbstract
{
    public function __toString(): string
    {
        return sprintf(
            '%s%s%s',
            (count($this->elements) > 1) ? '(' : '',
            implode(' AND ', $this->elements->getValues()),
            (count($this->elements) > 1) ? ')' : ''
        );
    }
}
