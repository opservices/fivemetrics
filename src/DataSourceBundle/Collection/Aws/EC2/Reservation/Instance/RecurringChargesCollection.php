<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 10/03/17
 * Time: 15:59
 */

namespace DataSourceBundle\Collection\Aws\EC2\Reservation\Instance;

use EssentialsBundle\Collection\TypedCollectionAbstract;

/**
 * Class RecurringChargesCollection
 * @package DataSourceBundle\InstanceCollection\Aws\EC2\Reservation\Instance
 */
class RecurringChargesCollection extends TypedCollectionAbstract
{
    public function getClass(): string
    {
        return 'DataSourceBundle\Entity\Aws\EC2\Reservation\Instance\RecurringCharges';
    }

    /**
     * {@inheritDoc}
     */
    protected function onChanged($added = null, $removed = null)
    {
    }
}

