<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 20/01/17
 * Time: 15:30
 */

namespace DataSourceBundle\Collection\Aws\EC2;

use EssentialsBundle\Collection\TypedCollectionAbstract;

/**
 * Class BlockDeviceMappingCollection
 * @package DataSourceBundle\InstanceCollection\Aws\EC2
 */
class BlockDeviceMappingCollection extends TypedCollectionAbstract
{
    public function getClass(): string
    {
        return 'DataSourceBundle\Entity\Aws\EC2\Instance\BlockDeviceMapping';
    }

    /**
     * {@inheritDoc}
     */
    protected function onChanged($added = null, $removed = null)
    {
    }
}
