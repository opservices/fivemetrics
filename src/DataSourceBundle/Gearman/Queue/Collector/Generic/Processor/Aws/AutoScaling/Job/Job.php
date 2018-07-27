<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 05/03/17
 * Time: 21:31
 */

namespace DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScaling\Job;

use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\JobAbstract;

/**
 * Class Job
 * @package DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScaling\Job
 */
class Job extends JobAbstract
{
    /**
     * @return string
     */
    public function getProcessor(): string
    {
        return 'Aws\\AutoScaling\\AutoScaling';
    }
}
