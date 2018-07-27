<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 24/01/17
 * Time: 16:07
 */

namespace DataSourceBundle\Collection\Gearman\Metadata;

use EssentialsBundle\Collection\TypedCollectionAbstract;

/**
 * Class MetadataCollection
 * @package DataSourceBundle\Collection\Gearman\Metadata
 */
class MetadataCollection extends TypedCollectionAbstract
{
    public function getClass(): string
    {
        return 'DataSourceBundle\Entity\Gearman\Metadata\Metadata';
    }

    /**
     * {@inheritDoc}
     */
    protected function onChanged($added = null, $removed = null)
    {
    }
}
