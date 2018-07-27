<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 20/01/17
 * Time: 15:36
 */

namespace DataSourceBundle\Collection\Aws\EC2;

use EssentialsBundle\Collection\TypedCollectionAbstract;

/**
 * Class EbsCollection
 * @package DataSourceBundle\InstanceCollection\Aws\EC2
 */
class EbsCollection extends TypedCollectionAbstract
{
    public function getClass(): string
    {
        return 'DataSourceBundle\Entity\Aws\EC2\Instance\Ebs';
    }

    /**
     * {@inheritDoc}
     */
    protected function onChanged($added = null, $removed = null)
    {
    }
}
