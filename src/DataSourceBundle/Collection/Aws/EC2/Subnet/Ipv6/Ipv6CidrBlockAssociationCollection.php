<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 20/01/17
 * Time: 15:36
 */

namespace DataSourceBundle\Collection\Aws\EC2\Subnet\Ipv6;

use EssentialsBundle\Collection\TypedCollectionAbstract;

/**
 * Class Ipv6CidrBlockAssociationCollection
 * @package DataSourceBundle\InstanceCollection\Aws\EC2\Subnet\Ipv6
 */
class Ipv6CidrBlockAssociationCollection extends TypedCollectionAbstract
{
    public function getClass(): string
    {
        return 'DataSourceBundle\Entity\Aws\EC2\Subnet\Ipv6\CidrBlockAssociation';
    }

    /**
     * {@inheritDoc}
     */
    protected function onChanged($added = null, $removed = null)
    {
    }
}
