<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 10/02/17
 * Time: 17:51
 */

namespace DataSourceBundle\Entity\Aws\CloudWatch;

use DataSourceBundle\Collection\Aws\CloudWatch\DimensionCollection;
use DataSourceBundle\Entity\Aws\EntityAbstract;

/**
 * Class Metric
 * @package Entity\Aws\CloudWatch
 */
class Metric extends EntityAbstract
{
    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var string
     */
    protected $metricName;

    /**
     * @var DimensionCollection
     */
    protected $dimensions;

    /**
     * Metric constructor.
     * @param string $namespace
     * @param string $metricName
     * @param DimensionCollection $dimensions
     */
    public function __construct(
        string $namespace,
        string $metricName,
        DimensionCollection $dimensions
    ) {
        $this->setNamespace($namespace)
            ->setMetricName($metricName)
            ->setDimensions($dimensions);
    }

    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @param string $namespace
     * @return Metric
     */
    public function setNamespace(string $namespace): Metric
    {
        $this->namespace = $namespace;
        return $this;
    }

    /**
     * @return string
     */
    public function getMetricName(): string
    {
        return $this->metricName;
    }

    /**
     * @param string $metricName
     * @return Metric
     */
    public function setMetricName(string $metricName): Metric
    {
        $this->metricName = $metricName;
        return $this;
    }

    /**
     * @return DimensionCollection
     */
    public function getDimensions(): DimensionCollection
    {
        return $this->dimensions;
    }

    /**
     * @param DimensionCollection $dimensions
     * @return Metric
     */
    public function setDimensions(DimensionCollection $dimensions): Metric
    {
        $this->dimensions = $dimensions;
        return $this;
    }
}
