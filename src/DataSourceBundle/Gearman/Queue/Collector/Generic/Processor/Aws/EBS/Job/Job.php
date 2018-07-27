<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 8/15/17
 * Time: 10:00 AM
 */

namespace DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EBS\Job;

use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\JobAbstract;

/**
 * Class Job
 * @package DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EBS\Job
 */
class Job extends JobAbstract
{

    /**
     * @return string
     */
    public function getProcessor(): string
    {
        return 'Aws\\EBS\\EBS';
    }
}
