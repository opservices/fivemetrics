<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 25/03/17
 * Time: 13:40
 */

namespace EssentialsBundle\Collection;

use EssentialsBundle\Exception\Exceptions;

/**
 * Class TypedCollectionAbstract
 * @package DataSourceBundle\InstanceCollection
 */
abstract class TypedCollectionAbstract extends ArrayCollection
{
    /**
     * TypedCollectionAbstract constructor.
     * @param array $elements
     */
    public function __construct(array $elements = [])
    {
        foreach ($elements as $element) {
            $this->add($element);
        }
    }

    /**
     * @param $object
     * @return bool
     */
    public function isValid($object)
    {
        return (is_a($object, $this->getClass()));
    }

    /**
     * @param mixed $item
     * @return ArrayCollection
     */
    public function add($item): ArrayCollection
    {
        if (! $this->isValid($item)) {
            throw new \InvalidArgumentException(
                get_called_class() . ' - An invalid value has been provided.',
                Exceptions::VALIDATION_ERROR
            );
        }

        parent::add($item);
        $this->onChanged($item);

        return $this;
    }

    /**
     * @return string
     */
    abstract public function getClass(): string;
    
    /**
     * @param null $added
     * @param null $removed
     * @return mixed
     */
    protected function onChanged($added = null, $removed = null)
    {
    }

    /**
     * @param int|string $index
     * @return mixed|null
     */
    public function remove($index)
    {
        $element = parent::remove($index);

        if ($element) {
            $this->onChanged(null, $element);
        }

        return $element;
    }

    /**
     * @param mixed $element
     * @return mixed|null
     */
    public function removeElement($element)
    {
        $element = parent::removeElement($element);

        if ($element) {
            $this->onChanged(null, $element);
        }

        return $element;
    }

    /**
     * {@inheritDoc}
     */
    public function set($key, $value): ArrayCollection
    {
        if (! $this->isValid($value)) {
            throw new \InvalidArgumentException(
                get_called_class() . ' - An invalid value has been provided.'
            );
        }

        $added   = clone($value);
        $removed = $this->get($key);

        parent::set($key, $value);

        $this->onChanged($added, $removed);

        return $this;
    }

    /**
     * @param TypedCollectionAbstract $collection
     * @return TypedCollectionAbstract
     */
    public function concat(TypedCollectionAbstract $collection): TypedCollectionAbstract
    {
        foreach ($collection as $item) {
            $this->add($item);
        }
        return $this;
    }

    /**
     * @param int $index
     * @return mixed
     */
    public function at(int $index)
    {
        if (isset($this->elements[$index])) {
            return $this->elements[$index];
        }

        throw new \OutOfBoundsException(
            "Index " . $index . " not exists as a collection index"
        );
    }

    public function jsonSerialize()
    {
        return $this->getObjectVarsRecursive($this->elements);
    }
}
