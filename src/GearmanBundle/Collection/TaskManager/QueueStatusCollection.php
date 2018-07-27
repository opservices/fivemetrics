<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 24/02/17
 * Time: 11:31
 */

namespace GearmanBundle\Collection\TaskManager;

use EssentialsBundle\Collection\TypedCollectionAbstract;

/**
 * Class QueueStatusCollection
 * @package GearmanBundle\Collection\TaskManager
 */
class QueueStatusCollection extends TypedCollectionAbstract
{
    /**
     * @var array
     */
    protected $indexes = [];

    public function getClass(): string
    {
        return 'GearmanBundle\TaskManager\QueueStatus';
    }

    /**
     * {@inheritDoc}
     */
    protected function onChanged($added = null, $removed = null)
    {
        $this->last();
        $index = $this->key();

        if ($removed) {
            $key   = $removed->getQueueName();
            $index = $this->indexes[$key];
            unset($this->indexes[$key]);
        }

        if ($added) {
            $key = $added->getQueueName();
            $this->indexes[$key] = $index;
        }
    }

    /**
     * @param string $queueName
     * @return mixed
     */
    public function find(string $queueName)
    {
        return (isset($this->indexes[$queueName]))
            ? $this->elements[$this->indexes[$queueName]]
            : null;
    }
}
