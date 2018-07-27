<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 20/01/17
 * Time: 16:18
 */

namespace DataSourceBundle\Collection\Aws\Tag;

use EssentialsBundle\Collection\TypedCollectionAbstract;
use DataSourceBundle\Entity\Aws\Tag\Tag;

/**
 * Class TagCollection
 * @package InstanceCollection\Aws\Tag
 */
class TagCollection extends TypedCollectionAbstract
{
    /**
     * @var array
     */
    protected $indexes = [];

    /**
     * @return string
     */
    public function getClass(): string
    {
        return Tag::class;
    }

    /**
     * {@inheritDoc}
     */
    protected function onChanged($added = null, $removed = null)
    {
        /** @var $added Tag */
        /** @var $removed Tag */

        $this->last();
        $index = $this->key();

        if ($removed) {
            $key   = $removed->getKey();
            $index = $this->indexes[$key];
            unset($this->indexes[$key]);
        }

        if ($added) {
            $key = $added->getKey();
            $this->indexes[$key] = $index;
        }
    }

    /**
     * @param string $key
     * @return Tag|null
     */
    public function find(string $key)
    {
        return (isset($this->indexes[$key]))
            ? $this->elements[$this->indexes[$key]]
            : null;
    }

    public function __toString(): string
    {
        return implode(',', $this->elements);
    }
}
