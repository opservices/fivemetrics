<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 24/01/17
 * Time: 16:07
 */

namespace DataSourceBundle\Collection\Aws\CloudWatch;

use EssentialsBundle\Collection\TypedCollectionAbstract;

/**
 * Class MetricCollection
 * @package DataSourceBundle\InstanceCollection\Aws\CloudWatch
 */
class MetricCollection extends TypedCollectionAbstract
{
    public function getClass(): string
    {
        return 'DataSourceBundle\Entity\Aws\CloudWatch\Metric';
    }

    /**
     * {@inheritDoc}
     */
    protected function onChanged($added = null, $removed = null)
    {
    }
}
