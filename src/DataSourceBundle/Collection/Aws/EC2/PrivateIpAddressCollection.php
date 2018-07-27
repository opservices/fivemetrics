<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 20/01/17
 * Time: 16:18
 */

namespace DataSourceBundle\Collection\Aws\EC2;

use EssentialsBundle\Collection\TypedCollectionAbstract;

/**
 * Class PrivateIpAddressCollection
 * @package DataSourceBundle\InstanceCollection\Aws\EC2
 */
class PrivateIpAddressCollection extends TypedCollectionAbstract
{
    public function getClass(): string
    {
        return 'DataSourceBundle\Entity\Aws\EC2\NetworkInterface\PrivateIpAddress';
    }

    /**
     * {@inheritDoc}
     */
    protected function onChanged($added = null, $removed = null)
    {
    }
}
