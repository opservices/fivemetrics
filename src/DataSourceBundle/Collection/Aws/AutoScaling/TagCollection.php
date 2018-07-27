<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 20/01/17
 * Time: 15:36
 */

namespace DataSourceBundle\Collection\Aws\AutoScaling;

use EssentialsBundle\Collection\TypedCollectionAbstract;

/**
 * Class TagCollection
 * @package DataSourceBundle\InstanceCollection\Aws\AutoScaling
 */
class TagCollection extends TypedCollectionAbstract
{
    public function getClass(): string
    {
        return 'DataSourceBundle\Entity\Aws\AutoScaling\Tag';
    }

    /**
     * {@inheritDoc}
     */
    protected function onChanged($added = null, $removed = null)
    {
    }
}
