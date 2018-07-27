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
 * Class SecurityGroupCollection
 * @package DataSourceBundle\InstanceCollection\Aws\EC2
 */
class SecurityGroupCollection extends TypedCollectionAbstract
{
    public function getClass(): string
    {
        return 'DataSourceBundle\Entity\Aws\EC2\SecurityGroup\SecurityGroup';
    }

    /**
     * {@inheritDoc}
     */
    protected function onChanged($added = null, $removed = null)
    {
    }
}
