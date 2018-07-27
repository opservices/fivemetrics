<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 20/01/17
 * Time: 16:18
 */

namespace EssentialsBundle\Collection\Tag;

use EssentialsBundle\Collection\TypedCollectionAbstract;
use EssentialsBundle\Entity\Tag\Tag;

/**
 * Class TagCollection
 * @package EssentialsBundle\Collection\Tag
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
     * @param null $added
     * @param null $removed
     */
    protected function onChanged($added = null, $removed = null)
    {
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
     * @return mixed
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

    protected function collectionToArray(bool $encoded): array
    {
        $array = [];
        foreach ($this->elements as $tag) {
            /** @var Tag $tag */
            $key = $tag->getKey();
            $value = $tag->getValue();

            if ($encoded) {
                $key = urlencode($key);
                $value = urlencode($value);
            }

            $array[$key] = $value;
        }

        return $array;
    }

    /**
     * @return array
     */
    public function toInfluxTagArray(): array
    {
        return $this->collectionToArray(true);
    }
}
