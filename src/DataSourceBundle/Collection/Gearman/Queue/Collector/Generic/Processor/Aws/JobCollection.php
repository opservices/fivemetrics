<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 24/02/17
 * Time: 11:31
 */

namespace DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws;

use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\JobAbstract;

/**
 * Class JobCollection
 * @package DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws
 */
class JobCollection extends \DataSourceBundle\Collection\Gearman\Job\JobCollection
{
    public function getClass(): string
    {
        return JobAbstract::class;
    }
}
