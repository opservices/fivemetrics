<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 05/02/17
 * Time: 10:33
 */

namespace DataSourceBundle\Collection\Aws\ElasticLoadBalancer;

use EssentialsBundle\Collection\TypedCollectionAbstract;

/**
 * Class InstanceHealthCollection
 * @package DataSourceBundle\InstanceCollection\Aws\ElasticLoadBalancer
 */
class InstanceHealthCollection extends TypedCollectionAbstract
{
    public function getClass(): string
    {
        return 'DataSourceBundle\Entity\Aws\ElasticLoadBalancer\InstanceHealth';
    }

    /**
     * {@inheritDoc}
     */
    protected function onChanged($added = null, $removed = null)
    {
    }
}
