<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 7/27/17
 * Time: 11:56 AM
 */

namespace DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier\Job;

use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\JobAbstract;

/**
 * Class Job
 * @package DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier\Job
 */
class Job extends JobAbstract
{

    /**
     * @return string
     */
    public function getProcessor(): string
    {
        return 'Aws\\Glacier\\Glacier';
    }
}
