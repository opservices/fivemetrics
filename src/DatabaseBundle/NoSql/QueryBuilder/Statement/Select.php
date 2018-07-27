<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 31/05/17
 * Time: 11:14
 */

namespace DatabaseBundle\NoSql\QueryBuilder\Statement;

use DatabaseBundle\Collection\NoSql\QueryBuilder\ColumnCollection;
use DatabaseBundle\NoSql\QueryBuilder\QueryElementAbstract;
use EssentialsBundle\Exception\Exceptions;

/**
 * Class Select
 * @package DatabaseBundle\NoSql\QueryBuilder\Statement
 */
class Select extends QueryElementAbstract
{
    /**
     * @var ColumnCollection
     */
    protected $columns;

    /**
     * Select constructor.
     * @param ColumnCollection $columns
     */
    public function __construct(ColumnCollection $columns)
    {
        $this->setColumns($columns);
    }

    /**
     * @param ColumnCollection $columns
     * @return Select
     */
    protected function setColumns(ColumnCollection $columns): Select
    {
        if ($columns->isEmpty()) {
            throw new \InvalidArgumentException(
                'At least one column must be provided.',
                Exceptions::VALIDATION_ERROR
            );
        }

        $this->columns = $columns;

        return $this;
    }

    public function __toString(): string
    {
        return sprintf(
            '%s %s',
            $this->getElementName(),
            $this->columns
        );
    }
}
