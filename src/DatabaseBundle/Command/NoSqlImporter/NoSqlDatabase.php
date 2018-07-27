<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 14/08/17
 * Time: 16:54
 */

namespace DatabaseBundle\Command\NoSqlImporter;

use InfluxDB\Database;

class NoSqlDatabase
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var Database
     */
    protected $influxDb;

    /**
     * @var NoSqlMeasurementCollection
     */
    protected $measurements;

    /**
     * NoSqlDatabase constructor.
     * @param string $name
     * @param Database $influxDb
     * @param NoSqlMeasurementCollection|null $measurements
     */
    public function __construct(
        string $name,
        Database $influxDb,
        NoSqlMeasurementCollection $measurements = null
    ) {
        $this->name = $name;
        $this->influxDb = $influxDb;
        $this->measurements = (is_null($measurements))
            ? new NoSqlMeasurementCollection()
            : $measurements;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Database
     */
    public function getInfluxDb(): Database
    {
        return $this->influxDb;
    }

    /**
     * @return NoSqlMeasurementCollection
     */
    public function getMeasurements(): NoSqlMeasurementCollection
    {
        return $this->measurements;
    }

    /**
     * @param NoSqlMeasurementCollection $measurements
     * @return NoSqlDatabase
     */
    public function setMeasurements(NoSqlMeasurementCollection $measurements): NoSqlDatabase
    {
        $this->measurements = $measurements;
        return $this;
    }
}
