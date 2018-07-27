<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 05/03/17
 * Time: 19:31
 */

namespace DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws;

use EssentialsBundle\Entity\EntityAbstract;

/**
 * Class Filter
 * @package DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws
 */
class Filter extends EntityAbstract
{
    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var array
     */
    protected $measurementNames;

    /**
     * Filter constructor.
     * @param string $namespace
     * @param array $measurementNames
     */
    public function __construct(
        string $namespace,
        array $measurementNames
    ) {
        $this->setNamespace($namespace)
            ->setMeasurementNames($measurementNames);
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
     * @return Filter
     */
    public function setNamespace(string $namespace): Filter
    {
        $this->namespace = $namespace;
        return $this;
    }

    /**
     * @return array
     */
    public function getMeasurementNames(): array
    {
        return $this->measurementNames;
    }

    /**
     * @param mixed $measurementNames
     * @return Filter
     */
    public function setMeasurementNames($measurementNames): Filter
    {
        $this->measurementNames = $measurementNames;
        return $this;
    }
}
