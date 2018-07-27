<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 31/05/17
 * Time: 12:55
 */

namespace DatabaseBundle\Command\NoSqlImporter;

use DatabaseBundle\NoSql\QueryBuilder\QueryElementInterface;
use EssentialsBundle\Collection\TypedCollectionAbstract;

/**
 * Class NoSqlDatabaseCollection
 * @package DatabaseBundle\Collection\NoSql\QueryBuilder
 */
class NoSqlDatabaseCollection extends TypedCollectionAbstract implements QueryElementInterface
{
    /**
     * @var array
     */
    protected $indexes = [];

    public function getClass(): string
    {
        return NoSqlDatabase::class;
    }

    protected function onChanged($added = null, $removed = null)
    {
        /** @var $added NoSqlDatabase */
        /** @var $removed NoSqlDatabase */

        $this->last();
        $index = $this->key();

        if ($removed) {
            $key   = $removed->getName();
            $index = $this->indexes[$key];
            unset($this->indexes[$key]);
        }

        if ($added) {
            $key = $added->getName();
            $this->indexes[$key] = $index;
        }
    }

    public function __toString(): string
    {
        return implode(', ', $this->elements);
    }

    /**
     * @param string $key
     * @return NoSqlDatabase|null
     */
    public function find(string $key)
    {
        return (isset($this->indexes[$key]))
            ? $this->elements[$this->indexes[$key]]
            : null;
    }
}
