<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 31/05/17
 * Time: 18:11
 */

namespace DatabaseBundle\NoSql\QueryBuilder\Statement;

use DatabaseBundle\Collection\NoSql\QueryBuilder\ColumnCollection;
use DatabaseBundle\NoSql\QueryBuilder\Column;
use DatabaseBundle\NoSql\QueryBuilder\Functions\Time;
use DatabaseBundle\NoSql\QueryBuilder\QueryElementAbstract;
use EssentialsBundle\Exception\Exceptions;

/**
 * Class GroupBy
 * @package DatabaseBundle\NoSql\QueryBuilder\Statement
 */
class GroupBy extends QueryElementAbstract
{
    /**
     * @var Time
     */
    protected $time;

    /**
     * @var ColumnCollection
     */
    protected $columns;

    /**
     * GroupBy constructor.
     * @param string|null $timeInterval
     * @param array $columns
     */
    public function __construct(string $timeInterval = null, array $columns = null)
    {
        (empty($timeInterval)) ?: $this->setTime($timeInterval);
        (empty($columns)) ?: $this->setColumns($columns);

        $this->validate();
    }

    protected function validate()
    {
        if ((empty($this->time)) && (empty($this->columns))) {
            throw new \InvalidArgumentException(
                'A group by must have at least one tag or time interval.',
                Exceptions::VALIDATION_ERROR
            );
        }
    }

    /**
     * @param array $columns
     * @return GroupBy
     */
    protected function setColumns(array $columns): GroupBy
    {
        $this->columns = new ColumnCollection();

        foreach ($columns as $column) {
            $this->columns->add(new Column(urlencode($column)));
        }

        return $this;
    }

    /**
     * @param string $time
     * @return $this
     */
    protected function setTime(string $time)
    {
        $this->time = new Time($time);
        return $this;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $params = [];

        if ($this->time) {
            $params[] = $this->time;
        }

        if ($this->columns) {
            $params[] = $this->columns;
        }

        return 'GROUP BY ' . implode(', ', $params);
    }
}
