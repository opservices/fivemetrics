<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 03/02/17
 * Time: 13:55
 */

namespace DataSourceBundle\Collection\Aws\AutoScaling;

use EssentialsBundle\Collection\TypedCollectionAbstract;

/**
 * Class AutoScalingGroupCollection
 * @package DataSourceBundle\InstanceCollection\Aws\AutoScaling
 */
class AutoScalingGroupCollection extends TypedCollectionAbstract
{
    public function getClass(): string
    {
        return 'DataSourceBundle\Entity\Aws\AutoScaling\AutoScalingGroup';
    }

    /**
     * {@inheritDoc}
     */
    protected function onChanged($added = null, $removed = null)
    {
    }
}
