<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 09/03/17
 * Time: 18:33
 */

namespace DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor;

use EssentialsBundle\Collection\TypedCollectionAbstract;

/**
 * Class ConfigurationCollection
 * @package DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor
 */
class ConfigurationCollection extends TypedCollectionAbstract
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
        return 'DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Configuration';
    }

    /**
     * {@inheritDoc}
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
