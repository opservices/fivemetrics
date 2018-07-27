<?php

namespace DataSourceBundle\Aws;

use EssentialsBundle\Collection\Metric\MetricCollection;
use EssentialsBundle\Entity\Metric\RealTimeData;

/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 17/01/17
 * Time: 16:43
 */
interface MeasurementInterface
{
    /**
     * @return MetricCollection
     */
    public function getMetrics(): MetricCollection;

    /**
     * @return RealTimeData|null
     */
    public function getRealTimeData();
}
