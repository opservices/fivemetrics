<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 04/02/17
 * Time: 16:02
 */

namespace DataSourceBundle\Collection\Aws\ElasticLoadBalancer\ListenerDescription;

use EssentialsBundle\Collection\TypedCollectionAbstract;

/**
 * Class ListenerDescriptionCollection
 * @package DataSourceBundle\InstanceCollection\Aws\ElasticLoadBalancer\ListenerDescription
 */
class ListenerDescriptionCollection extends TypedCollectionAbstract
{
    public function getClass(): string
    {
        return 'DataSourceBundle\Entity\Aws\ElasticLoadBalancer\ListenerDescription\ListenerDescription';
    }

    /**
     * {@inheritDoc}
     */
    protected function onChanged($added = null, $removed = null)
    {
    }
}
