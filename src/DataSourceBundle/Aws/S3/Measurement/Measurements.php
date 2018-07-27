<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 6/6/17
 * Time: 11:52 AM
 */

namespace DataSourceBundle\Aws\S3\Measurement;

use DataSourceBundle\Aws\MeasurementInterface;
use EssentialsBundle\Collection\Metric\MetricCollection;

/**
 * Class Measurements
 * @package DataSourceBundle\Aws\S3\Measurement
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
