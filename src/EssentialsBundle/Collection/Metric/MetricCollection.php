<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 24/01/17
 * Time: 16:07
 */

namespace EssentialsBundle\Collection\Metric;

use EssentialsBundle\Collection\TypedCollectionAbstract;

/**
 * Class MetricCollection
 * @package EssentialsBundle\InstanceCollection\Metric
 */
class MetricCollection extends TypedCollectionAbstract
{
    public function getClass(): string
    {
        return 'EssentialsBundle\Entity\Metric\Metric';
    }

    /**
     * {@inheritDoc}
     */
    protected function onChanged($added = null, $removed = null)
    {
    }
}
