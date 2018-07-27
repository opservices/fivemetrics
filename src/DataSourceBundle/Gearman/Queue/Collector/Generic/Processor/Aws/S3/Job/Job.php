<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 6/12/17
 * Time: 5:24 PM
 */

namespace DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\S3\Job;

use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\JobAbstract;

/**
 * Class Job
 * @package DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\S3\Job
 */
class Job extends JobAbstract
{
    /**
     * @return string
     */
    public function getProcessor(): string
    {
        return 'Aws\\S3\\S3';
    }
}
