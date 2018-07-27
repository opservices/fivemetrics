<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 24/02/17
 * Time: 11:31
 */

namespace DataSourceBundle\Collection\Gearman\Job;

use EssentialsBundle\Collection\TypedCollectionAbstract;
use GearmanBundle\Job\JobInterface;

/**
 * Class JobCollection
 * @package DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Job
 */
class JobCollection extends TypedCollectionAbstract
{
    public function getClass(): string
    {
        return JobInterface::class;
    }

    /**
     * {@inheritDoc}
     */
    protected function onChanged($added = null, $removed = null)
    {
    }
}
