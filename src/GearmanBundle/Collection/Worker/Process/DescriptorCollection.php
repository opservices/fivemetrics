<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 24/02/17
 * Time: 11:31
 */

namespace GearmanBundle\Collection\Worker\Process;

use EssentialsBundle\Collection\TypedCollectionAbstract;
use GearmanBundle\Worker\Process\Descriptor;

/**
 * Class DescriptorCollection
 * @package Collection\Gearman\Worker
 */
class DescriptorCollection extends TypedCollectionAbstract
{
    public function getClass(): string
    {
        return 'GearmanBundle\Worker\Process\Descriptor';
    }

    /**
     * {@inheritDoc}
     */
    protected function onChanged($added = null, $removed = null)
    {
        if ($this->count() > 3) {
            throw new \RuntimeException(
                "It's allowed only up to three descriptors."
            );
        }
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return array_map(function (Descriptor $el) {
            return $el->toArray();
        }, $this->elements);
    }
}
