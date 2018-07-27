<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 24/02/17
 * Time: 11:31
 */

namespace DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer;

use EssentialsBundle\Collection\TypedCollectionAbstract;

/**
 * Class FilterCollection
 * @package GearmanBundle\Collection\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer
 */
class FilterCollection extends TypedCollectionAbstract
{
    public function getClass(): string
    {
        return 'DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer\Filter';
    }

    /**
     * {@inheritDoc}
     */
    protected function onChanged($added = null, $removed = null)
    {
    }
}
