<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 7/25/17
 * Time: 2:10 PM
 */

namespace DataSourceBundle\Aws\Glacier\Measurement;

use DataSourceBundle\Aws\MeasurementInterface;
use EssentialsBundle\Collection\Metric\MetricCollection;

/**
 * Class Measurements
 * @package DataSourceBundle\Aws\Glacier\Measurement
 */
class Measurements
{
    /**
     * @var mixed
     */
    protected $measurements;

    /**
     * @param MeasurementInterface $measurement
     * @return Measurements
     */
    public function addMeasurement(MeasurementInterface $measurement): Measurements
    {
        $this->measurements[] = $measurement;
        return $this;
    }

    /**
     * @return MetricCollection
     */
    public function getMetrics(): MetricCollection
    {
        $metrics = new MetricCollection();

        foreach ($this->measurements as $measurement) {
            $metrics->concat($measurement->getMetrics());
        }

        return $metrics;
    }
}
