<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 04/02/17
 * Time: 21:33
 */

namespace DataSourceBundle\Collection\Aws\AutoScaling;

use EssentialsBundle\Collection\TypedCollectionAbstract;

/**
 * Class ActivityCollection
 * @package DataSourceBundle\InstanceCollection\Aws\AutoScaling
 */
class ActivityCollection extends TypedCollectionAbstract
{
    public function getClass(): string
    {
        return 'DataSourceBundle\Entity\Aws\AutoScaling\Activity';
    }

    /**
     * {@inheritDoc}
     */
    protected function onChanged($added = null, $removed = null)
    {
    }
}
