<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 24/01/17
 * Time: 16:07
 */

namespace EssentialsBundle\Collection\Id;

use EssentialsBundle\Collection\TypedCollectionAbstract;

/**
 * Class MetricCollection
 * @package EssentialsBundle\InstanceCollection\Metric
 */
class IdCollection extends TypedCollectionAbstract
{
    public function getClass(): string
    {
        return 'EssentialsBundle\Entity\Id\Id';
    }

    /**
     * {@inheritDoc}
     */
    protected function onChanged($added = null, $removed = null)
    {
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return implode('.', $this->elements);
    }
}
