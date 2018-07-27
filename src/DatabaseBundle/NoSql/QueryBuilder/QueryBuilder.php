<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 31/05/17
 * Time: 14:31
 */

namespace DatabaseBundle\NoSql\QueryBuilder;

use DatabaseBundle\Collection\NoSql\QueryBuilder\ColumnCollection;
use DatabaseBundle\Collection\NoSql\QueryBuilder\ConditionCollection;
use DatabaseBundle\Collection\NoSql\QueryBuilder\FilterCollection;
use DatabaseBundle\NoSql\QueryBuilder\Functions\Aggregation\AggregationProvider;
use DatabaseBundle\NoSql\QueryBuilder\Functions\Fill;
use DatabaseBundle\NoSql\QueryBuilder\Operators\LogicalAnd;
use DatabaseBundle\NoSql\QueryBuilder\Operators\LogicalOr;
use DatabaseBundle\NoSql\QueryBuilder\Statement\From;
use DatabaseBundle\NoSql\QueryBuilder\Statement\GroupBy;
use DatabaseBundle\NoSql\QueryBuilder\Statement\Limit;
use DatabaseBundle\NoSql\QueryBuilder\Statement\OrderBy;
use DatabaseBundle\NoSql\QueryBuilder\Statement\Select;
use DatabaseBundle\NoSql\QueryBuilder\Statement\Where;
use EssentialsBundle\Entity\TimePeriod\TimePeriodInterface;
use EssentialsBundle\KernelLoader;

/**
 * Class QueryBuilder
 * @package DatabaseBundle\NoSql\QueryBuilder
 */
class QueryBuilder
{
    /**
     * @param array $query
     * @param string $series
     * @return Query
     */
    public function getQuery(array $query, string $series): Query
    {
        $timePeriod = KernelLoader::load()
            ->getContainer()
            ->get('timeperiods')
            ->factory($query['period']);

        $queryObject = $this->buildQuery($timePeriod, $query['query'], $series);
        (isset($query['query']['limit'])) ?: $queryObject->setLimit(new Limit(Limit::MAX));

        return $queryObject;
    }

    /**
     * @param TimePeriodInterface $timePeriod
     * @param array $query
     * @param string $series
     * @return Query
     */
    protected function buildQuery(
        TimePeriodInterface $timePeriod,
        array $query,
        string $series
    ): Query {
        $from = (empty($query['query']))
            ? new From($series)
            : new From($this->buildQuery($timePeriod, $query['query'], $series));

        $select = new Select($this->buildColumns($query));
        $filters = ((isset($query['filter'])) && (is_array($query['filter'])))
            ? $query['filter'] : [];

        $where = new Where($this->buildFilter($timePeriod, $filters));

        $time = (isset($query['groupBy']['time'])) ? $query['groupBy']['time'] : null;
        $tags = (isset($query['groupBy']['tags'])) ? $query['groupBy']['tags'] : null;
        $groupBy = (isset($query['groupBy']))
            ? new GroupBy($time, $tags)
            : null;

        $orderBy = (isset($query['orderBy']))
            ? new OrderBy($query['orderBy'])
            : null;

        $fill  = (isset($query['fill'])) ? new Fill((int)$query['fill']) : null;
        $limit  = (isset($query['limit'])) ? new Limit($query['limit']) : null;

        return new Query($select, $from, $where, $groupBy, $orderBy, $fill, $limit);
    }

    /**
     * @param TimePeriodInterface $timePeriod
     * @param array $filters
     * @return FilterCollection
     */
    protected function buildFilter(
        TimePeriodInterface $timePeriod,
        array $filters
    ): FilterCollection {
        $filterCollection = new FilterCollection(new LogicalAnd());

        foreach ($filters as $key => $values) {
            $filterCollection->add(new ConditionCollection(new LogicalOr()));
            if (empty($values)) {
                $filterCollection->last()->add(new Condition(
                    urlencode($key),
                    '=',
                    null
                ));
            }

            foreach ($values as $value) {
                $filterCollection->last()->add(new Condition(
                    urlencode($key),
                    '=',
                    urlencode($value)
                ));
            }
        }

        $filterCollection->add(new ConditionCollection(
            new LogicalAnd(),
            [
                new Condition('time', '>', $timePeriod->getStart()),
                new Condition('time', '<', $timePeriod->getEnd()),
            ]
        ));

        return $filterCollection;
    }

    /**
     * @param array $query
     * @return ColumnCollection
     */
    protected function buildColumns(array $query): ColumnCollection
    {
        $columns = new ColumnCollection();
        $selectedColumns = [ 'value' ];

        if (isset($query['aggregation'])) {
            $columns->add(AggregationProvider::factory($query['aggregation']));
            $selectedColumns = [];

            if (! empty($query['columns'])) {
                $query['columns'] = array_values(array_filter($query['columns'], function($el) {
                    return ('value' != $el);
                }));
            }
        }

        if ((! empty($query['columns'])) && (is_array($query['columns']))) {
            $selectedColumns = $query['columns'];
        }

        foreach ($selectedColumns as $column) {
            $columns->add(new Column($column));
        }

        return $columns;
    }
}
