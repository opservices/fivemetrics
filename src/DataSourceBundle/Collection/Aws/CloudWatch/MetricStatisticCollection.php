<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 10/02/17
 * Time: 13:23
 */

namespace DataSourceBundle\Collection\Aws\CloudWatch;

use EssentialsBundle\Collection\TypedCollectionAbstract;

/**
 * Class MetricStatisticCollection
 * @package DataSourceBundle\InstanceCollection\Aws\CloudWatch
 */
class MetricStatisticCollection extends TypedCollectionAbstract
{
    /**
     * @return string
     */
    public function getClass(): string
    {
        return 'DataSourceBundle\Entity\Aws\CloudWatch\MetricStatistic';
    }

    /**
     * {@inheritDoc}
     */
    protected function onChanged($added = null, $removed = null)
    {
    }
}
