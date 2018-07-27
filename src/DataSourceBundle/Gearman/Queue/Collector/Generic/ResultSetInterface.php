<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 05/03/17
 * Time: 14:20
 */

namespace DataSourceBundle\Gearman\Queue\Collector\Generic;

use EssentialsBundle\Collection\Metric\MetricCollection;
use DataSourceBundle\Collection\Gearman\Job\JobCollection;

/**
 * Interface ResultSetInterface
 * @package DataSourceBundle\Gearman\Queue\Collector\Generic
 */
interface ResultSetInterface
{
    /**
     * @return JobCollection
     */
    public function getJobs(): JobCollection;

    /**
     * @return MetricCollection
     */
    public function getMetrics(): MetricCollection;
}
