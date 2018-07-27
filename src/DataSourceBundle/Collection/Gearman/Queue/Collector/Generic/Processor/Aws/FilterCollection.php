<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 24/02/17
 * Time: 11:31
 */

namespace DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws;

use EssentialsBundle\Collection\TypedCollectionAbstract;

/**
 * Class FilterCollection
 * @package DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws
 */
class FilterCollection extends TypedCollectionAbstract
{
    public function getClass(): string
    {
        return 'DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\Filter';
    }

    /**
     * {@inheritDoc}
     */
    protected function onChanged($added = null, $removed = null)
    {
    }
}
