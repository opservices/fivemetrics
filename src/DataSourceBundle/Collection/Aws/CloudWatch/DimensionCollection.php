<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 10/02/17
 * Time: 13:23
 */

namespace DataSourceBundle\Collection\Aws\CloudWatch;

use EssentialsBundle\Collection\TypedCollectionAbstract;
use DataSourceBundle\Entity\Aws\CloudWatch\Dimension;

/**
 * Class DimensionCollection
 * @package DataSourceBundle\InstanceCollection\Aws\CloudWatch
 */
class DimensionCollection extends TypedCollectionAbstract
{
    /**
     * @return string
     */
    public function getClass(): string
    {
        return 'DataSourceBundle\Entity\Aws\CloudWatch\Dimension';
    }

    /**
     * {@inheritDoc}
     */
    protected function onChanged($added = null, $removed = null)
    {
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return array_map(function (Dimension $datapoint) {
            return [
                'Name'  => $datapoint->getName(),
                'Value' => $datapoint->getValue()
            ];
        }, $this->elements);
    }
}
