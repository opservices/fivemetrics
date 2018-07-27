<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 23/02/17
 * Time: 08:41
 */

namespace GearmanBundle\Collection\Configuration;

use EssentialsBundle\Collection\TypedCollectionAbstract;

/**
 * Class JobServerCollection
 * @package GearmanBundle\Collection\Gearman\Configuration
 */
class JobServerCollection extends TypedCollectionAbstract
{
    /**
     * @return string
     */
    public function getClass(): string
    {
        return 'GearmanBundle\Entity\Configuration\JobServer';
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
        return implode(',', $this->elements);
    }

    /**
     * @return array on format acceped by GearmanClient::addServers.
     * @see http://php.net/manual/en/gearmanclient.addservers.php
     */
    public function toArray(): array
    {
        return array_map(function ($el) {
            return (string)$el;
        }, $this->elements);
    }
}
