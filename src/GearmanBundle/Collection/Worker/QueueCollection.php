<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 24/02/17
 * Time: 11:31
 */

namespace GearmanBundle\Collection\Worker;

use EssentialsBundle\Collection\TypedCollectionAbstract;

/**
 * Class QueueCollection
 * @package Collection\Gearman\Worker
 */
class QueueCollection extends TypedCollectionAbstract
{
    public function getClass(): string
    {
        return 'GearmanBundle\Worker\Queue';
    }

    /**
     * {@inheritDoc}
     */
    protected function onChanged($added = null, $removed = null)
    {
    }
}
