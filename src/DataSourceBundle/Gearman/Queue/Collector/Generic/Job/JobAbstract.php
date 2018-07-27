<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 21/05/17
 * Time: 00:08
 */

namespace DataSourceBundle\Gearman\Queue\Collector\Generic\Job;

use GearmanBundle\Job\Job;

/**
 * Class JobAbstract
 * @package DataSourceBundle\Gearman\Queue\Collector\Generic\Job
 */
abstract class JobAbstract extends Job implements JobInterface
{
}
