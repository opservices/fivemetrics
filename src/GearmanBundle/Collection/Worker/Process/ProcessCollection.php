<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 24/02/17
 * Time: 11:31
 */

namespace GearmanBundle\Collection\Worker\Process;

use EssentialsBundle\Collection\TypedCollectionAbstract;

/**
 * Class DescriptorCollection
 * @package Collection\Gearman\Worker
 */
class ProcessCollection extends TypedCollectionAbstract
{
    public function getClass(): string
    {
        return 'GearmanBundle\Worker\Process\Process';
    }

    /**
     * {@inheritDoc}
     */
    protected function onChanged($added = null, $removed = null)
    {
    }
}
