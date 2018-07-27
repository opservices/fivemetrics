<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 01/06/17
 * Time: 08:06
 */

namespace DatabaseBundle\NoSql\QueryBuilder\Statement;

use DatabaseBundle\Collection\NoSql\QueryBuilder\FilterCollection;
use DatabaseBundle\NoSql\QueryBuilder\QueryElementAbstract;
use EssentialsBundle\Exception\Exceptions;

/**
 * Class Where
 * @package DatabaseBundle\NoSql\QueryBuilder\Statement
 */
class Where extends QueryElementAbstract
{
    /**
     * @var FilterCollection
     */
    protected $filters;

    public function __construct(FilterCollection $filters)
    {
        $this->setFilters($filters);
    }

    /**
     * @param FilterCollection $filters
     * @return Where
     */
    protected function setFilters(FilterCollection $filters): Where
    {
        if ($filters->isEmpty()) {
            throw new \InvalidArgumentException(
                'A filter can\'t be empty.',
                Exceptions::VALIDATION_ERROR
            );
        }

        $this->filters = $filters;
        return $this;
    }

    public function __toString(): string
    {
        return sprintf(
            '%s %s',
            $this->getElementName(),
            $this->filters
        );
    }
}
