<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 25/09/17
 * Time: 14:50
 */

namespace CollectorBundle\Collect;

use EssentialsBundle\Collection\TypedCollectionAbstract;

class CollectCollection extends TypedCollectionAbstract
{
    /**
     * @var array
     */
    protected $indexes = [];

    public function getClass(): string
    {
        return Collect::class;
    }

    protected function onChanged($added = null, $removed = null)
    {
        $this->last();
        $index = $this->key();

        if ($removed) {
            /** @var Collect $removed */
            $key   = $removed->getId();
            $index = $this->indexes[$key];
            unset($this->indexes[$key]);
        }

        if ($added) {
            /** @var Collect $added */
            $key = $added->getId();
            $this->indexes[$key] = $index;
        }
    }

    /**
     * @param string $id
     * @return null
     */
    public function find($id)
    {
        return (isset($this->indexes[$id]))
            ? $this->elements[$this->indexes[$id]]
            : null;
    }

    public function remove($id)
    {
        return parent::remove($this->indexes[$id]);
    }
}
