<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 21/05/17
 * Time: 00:05
 */

namespace DataSourceBundle\Gearman\Queue\Collector\Generic\Job;

/**
 * Interface JobInterface
 * @package DataSourceBundle\Gearman\Queue\Collector\Generic\Job
 */
interface JobInterface extends \GearmanBundle\Job\JobInterface
{
    /**
     * @return string
     */
    public function getProcessor(): string;
}
