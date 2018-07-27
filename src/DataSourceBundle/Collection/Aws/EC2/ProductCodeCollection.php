<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 18/01/17
 * Time: 14:02
 */

namespace DataSourceBundle\Collection\Aws\EC2;

use EssentialsBundle\Collection\TypedCollectionAbstract;

/**
 * Class ProductCodeCollection
 * @package DataSourceBundle\InstanceCollection\Aws\EC2
 */
class ProductCodeCollection extends TypedCollectionAbstract
{
    public function getClass(): string
    {
        return 'DataSourceBundle\Entity\Aws\Ec2\Instance\ProductCode';
    }

    /**
     * {@inheritDoc}
     */
    protected function onChanged($added = null, $removed = null)
    {
    }
}
