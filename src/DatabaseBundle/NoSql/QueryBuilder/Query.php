<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 31/05/17
 * Time: 16:44
 */

namespace DatabaseBundle\NoSql\QueryBuilder;

use DatabaseBundle\NoSql\QueryBuilder\Functions\Fill;
use DatabaseBundle\NoSql\QueryBuilder\Statement\From;
use DatabaseBundle\NoSql\QueryBuilder\Statement\GroupBy;
use DatabaseBundle\NoSql\QueryBuilder\Statement\Limit;
use DatabaseBundle\NoSql\QueryBuilder\Statement\OrderBy;
use DatabaseBundle\NoSql\QueryBuilder\Statement\Select;
use DatabaseBundle\NoSql\QueryBuilder\Statement\Where;

/**
 * Class Query
 * @package DatabaseBundle\NoSql\QueryBuilder
 */
class Query implements QueryElementInterface
{
    /**
     * @var Select
     */
    protected $select;

    /**
     * @var From
     */
    protected $from;

    /**
     * @var Where
     */
    protected $where;

    /**
     * @var GroupBy
     */
    protected $groupBy;

    /**
     * @var OrderBy
     */
    protected $orderBy;


    /**
     * @var Fill
     */
    protected $fill;

    /**
     * @var Limit
     */
    protected $limit;

    /**
     * Query constructor.
     * @param Select $select
     * @param From $from
     * @param Where|null $where
     * @param GroupBy|null $groupBy
     * @param OrderBy|null $orderBy
     * @param Fill|null $fill
     * @param Limit|null $limit
     */
    public function __construct(
        Select $select,
        From $from,
        Where $where = null,
        GroupBy $groupBy = null,
        OrderBy $orderBy = null,
        Fill $fill = null,
        Limit $limit = null
    ) {
        $this->select  = $select;
        $this->from    = $from;
        $this->where   = (is_null($where)) ? '' : $where;
        $this->groupBy = (is_null($groupBy)) ? '' : $groupBy;
        $this->orderBy   = (is_null($orderBy)) ? '' : $orderBy;
        $this->fill   = (is_null($fill)) ? '' : $fill;
        $this->limit   = (is_null($limit)) ? '' : $limit;
    }

    public function __toString(): string
    {
        return implode(" ", array_values(get_object_vars($this)));
    }

    /**
     * @return Limit|string
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param Limit $limit
     */
    public function setLimit(Limit $limit)
    {
        $this->limit = $limit;
    }
}
