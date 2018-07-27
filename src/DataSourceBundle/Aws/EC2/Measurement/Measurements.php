<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 18/01/17
 * Time: 09:06
 */

namespace DataSourceBundle\Aws\EC2\Measurement;

use DataSourceBundle\Aws\MeasurementInterface;
use EssentialsBundle\Collection\Metric\MetricCollection;
use EssentialsBundle\Collection\Metric\RealTimeDataCollection;

/**
 * Class Measurements
 * @package DataSourceBundle\Aws\EC2\Measurement
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

    /**
     * @return RealTimeDataCollection
     */
    public function getRealTimeData(): RealTimeDataCollection
    {
        $data = new RealTimeDataCollection();

        foreach ($this->measurements as $measurement) {
            /** @var MeasurementInterface $measurement */
            if ($measurement->getRealTimeData()) {
                $data->add($measurement->getRealTimeData());
            }
        }

        return $data;
    }
}
