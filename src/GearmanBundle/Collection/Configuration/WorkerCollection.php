<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 23/02/17
 * Time: 08:41
 */

namespace GearmanBundle\Collection\Configuration;

use EssentialsBundle\Collection\TypedCollectionAbstract;
use GearmanBundle\Entity\Configuration\Worker;

/**
 * Class WorkerCollection
 * @package GearmanBundle\Collection\Configuration
 */
class WorkerCollection extends TypedCollectionAbstract
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
        return Worker::class;
    }

    /**
     * {@inheritDoc}
     */
    protected function onChanged($added = null, $removed = null)
    {
        $this->last();
        $index = $this->key();

        if ($removed) {
            $key   = $removed->getClass();
            $index = $this->indexes[$key];
            unset($this->indexes[$key]);
        }

        if ($added) {
            $key = $added->getClass();
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
