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
 * Class BackendServerDescriptionCollection
 * @package DataSourceBundle\InstanceCollection\Aws\ElasticLoadBalancer
 */
class BackendServerDescriptionCollection extends TypedCollectionAbstract
{
    public function getClass(): string
    {
        return 'DataSourceBundle\Entity\Aws\ElasticLoadBalancer\BackendServerDescription';
    }

    /**
     * {@inheritDoc}
     */
    protected function onChanged($added = null, $removed = null)
    {
    }
}
