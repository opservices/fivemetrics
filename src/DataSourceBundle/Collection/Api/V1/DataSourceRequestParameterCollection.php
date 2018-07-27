<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 14/09/17
 * Time: 08:36
 */

namespace DataSourceBundle\Collection\Api\V1;

use DataSourceBundle\Api\V1\DataSourceRequestParameter;
use EssentialsBundle\Collection\TypedCollectionAbstract;

class DataSourceRequestParameterCollection extends TypedCollectionAbstract
{
    /**
     * @var array
     */
    protected $indexes = [];

    public function getClass(): string
    {
        return DataSourceRequestParameter::class;
    }

    protected function onChanged($added = null, $removed = null)
    {
        /** @var DataSourceRequestParameter $added */
        /** @var DataSourceRequestParameter $removed */
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

    /**
     * @param string $key
     * @return mixed
     */
    public function find(string $key)
    {
        return (isset($this->indexes[$key]))
            ? $this->elements[$this->indexes[$key]]
            : null;
    }
}
