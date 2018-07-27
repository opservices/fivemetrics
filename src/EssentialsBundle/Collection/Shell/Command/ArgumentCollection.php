<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 20/01/17
 * Time: 16:18
 */

namespace EssentialsBundle\Collection\Shell\Command;

use EssentialsBundle\Collection\TypedCollectionAbstract;

/**
 * Class ArgumentCollection
 * @package EssentialsBundle\Collection\Shell\Command
 */
class ArgumentCollection extends TypedCollectionAbstract
{
    /**
     * @return string
     */
    public function getClass(): string
    {
        return 'EssentialsBundle\Entity\Shell\Command\Argument';
    }

    /**
     * {@inheritDoc}
     */
    protected function onChanged($added = null, $removed = null)
    {
    }


    /**
     * @return string
     */
    public function __toString(): string
    {
        $str = '';

        foreach ($this->elements as $argument) {
            $str .= ' ' . $argument;
        }

        return $str;
    }
}
