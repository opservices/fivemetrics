<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 24/02/17
 * Time: 11:31
 */

namespace GearmanBundle\Collection\Worker\Manager;

use EssentialsBundle\Collection\TypedCollectionAbstract;

/**
 * Class QueueCollection
 * @package GearmanBundle\Collection\Worker\Manager
 */
class QueueCollection extends TypedCollectionAbstract
{
    public function getClass(): string
    {
        return 'GearmanBundle\Worker\Manager\Queue';
    }

    /**
     * {@inheritDoc}
     */
    protected function onChanged($added = null, $removed = null)
    {
    }
}
