<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 04/02/17
 * Time: 22:57
 */

namespace DataSourceBundle\Collection\Aws\ElasticLoadBalancer;

use EssentialsBundle\Collection\TypedCollectionAbstract;

/**
 * Class ElasticLoadBalancerCollection
 * @package DataSourceBundle\InstanceCollection\Aws\ElasticLoadBalancer
 */
class ElasticLoadBalancerCollection extends TypedCollectionAbstract
{
    public function getClass(): string
    {
        return 'DataSourceBundle\Entity\Aws\ElasticLoadBalancer\ElasticLoadBalancer';
    }

    /**
     * {@inheritDoc}
     */
    protected function onChanged($added = null, $removed = null)
    {
    }
}
