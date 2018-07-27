<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 25/09/17
 * Time: 15:06
 */

namespace CollectorBundle\Collect;

use EssentialsBundle\Collection\TypedCollectionAbstract;

class ParameterCollection extends TypedCollectionAbstract
{
    /**
     * @var array
     */
    protected $indexes = [];

    public function getClass(): string
    {
        return Parameter::class;
    }

    /**
     * @param Parameter $added
     * @param Parameter $removed
     */
    protected function onChanged($added = null, $removed = null)
    {
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
