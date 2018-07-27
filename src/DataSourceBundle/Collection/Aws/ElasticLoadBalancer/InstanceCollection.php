<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 20/01/17
 * Time: 15:36
 */

namespace DataSourceBundle\Collection\Aws\ElasticLoadBalancer;

use EssentialsBundle\Collection\TypedCollectionAbstract;

/**
 * Class InstanceCollection
 * @package InstanceCollection\Aws\ElasticLoadBalancer
 */
class InstanceCollection extends TypedCollectionAbstract
{
    public function getClass(): string
    {
        return 'DataSourceBundle\Entity\Aws\ElasticLoadBalancer\Instance';
    }

    /**
     * {@inheritDoc}
     */
    protected function onChanged($added = null, $removed = null)
    {
    }
}
