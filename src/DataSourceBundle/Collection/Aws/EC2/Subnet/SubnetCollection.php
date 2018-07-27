<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 13/02/17
 * Time: 16:25
 */

namespace DataSourceBundle\Collection\Aws\EC2\Subnet;

use EssentialsBundle\Collection\TypedCollectionAbstract;

/**
 * Class SubnetCollection
 * @package DataSourceBundle\InstanceCollection\Aws\EC2\Subnet
 */
class SubnetCollection extends TypedCollectionAbstract
{
    public function getClass(): string
    {
        return 'DataSourceBundle\Entity\Aws\EC2\Subnet\Subnet';
    }

    /**
     * {@inheritDoc}
     */
    protected function onChanged($added = null, $removed = null)
    {
    }
}
