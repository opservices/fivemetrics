<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 25/09/17
 * Time: 14:50
 */

namespace CollectorBundle\Collect;

use EssentialsBundle\Collection\Metric\MetricCollection;
use EssentialsBundle\Collection\Metric\RealTimeDataCollection;
use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\Entity\EntityAbstract;
use GearmanBundle\Collection\Job\JobCollection;

class Collect extends EntityAbstract
{
    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var DataSource
     */
    protected $dataSource;

    /**
     * @var ParameterCollection
     */
    protected $parameters;

    /**
     * @var bool
     */
    protected $isEnabled;

    /**
     * @var DateTime
     */
    protected $lastUpdate;

    /**
     * @var JobCollection
     */
    protected $pendingJobs;

    /**
     * @var MetricCollection
     */
    protected $metrics;

    /**
     * @var RealTimeDataCollection
     */
    protected $realTimeData;

    /**
     * @var array
     */
    protected $errors = [];

    /**
     * Collect constructor.
     * @param mixed $id
     * @param DataSource $dataSource
     * @param ParameterCollection $parameters
     * @param bool $isEnabled
     * @param DateTime|null $lastUpdate
     * @param JobCollection|null $jobs
     * @param MetricCollection|null $metrics
     */
    public function __construct(
        $id,
        DataSource $dataSource,
        ParameterCollection $parameters,
        bool $isEnabled = true,
        DateTime $lastUpdate = null,
        JobCollection $jobs = null,
        MetricCollection $metrics = null,
        RealTimeDataCollection $realTime = null
    ) {
        $this->setId($id)
            ->setIsEnabled($isEnabled)
            ->setDataSource($dataSource)
            ->setParameters($parameters);

        (is_null($lastUpdate)) ?: $this->setLastUpdate($lastUpdate);
        $this->pendingJobs = (is_null($jobs)) ? new JobCollection() : $jobs;
        $this->metrics = (is_null($metrics)) ? new MetricCollection() : $metrics;
        $this->realTimeData = (is_null($realTime)) ? new RealTimeDataCollection() : $realTime;
    }

    public static function selfBuild(
        int $id,
        string $dataSourceName,
        int $dataSourceConcurrency,
        int $dataSourceCollectInterval,
        ParameterCollection $params = null
    ) {
        return new static(
            $id,
            new DataSource($dataSourceName, $dataSourceConcurrency, $dataSourceCollectInterval),
            (is_null($params)) ? new ParameterCollection() : $params
        );
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function addError($error): Collect
    {
        $this->errors[md5($error)] = $error;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Collect
     */
    public function setId($id): Collect
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return MetricCollection
     */
    public function getMetrics(): MetricCollection
    {
        return $this->metrics;
    }

    /**
     * @return DataSource
     */
    public function getDataSource(): DataSource
    {
        return $this->dataSource;
    }

    /**
     * @param DataSource $dataSource
     * @return Collect
     */
    public function setDataSource(DataSource $dataSource): Collect
    {
        $this->dataSource = $dataSource;
        return $this;
    }

    /**
     * @return ParameterCollection
     */
    public function getParameters(): ParameterCollection
    {
        return $this->parameters;
    }

    /**
     * @param ParameterCollection $parameters
     * @return Collect
     */
    public function setParameters(ParameterCollection $parameters): Collect
    {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }

    /**
     * @param bool $isEnabled
     * @return Collect
     */
    public function setIsEnabled(bool $isEnabled): Collect
    {
        $this->isEnabled = $isEnabled;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getLastUpdate()
    {
        return $this->lastUpdate;
    }

    /**
     * @param DateTime $lastUpdate
     * @return Collect
     */
    public function setLastUpdate(DateTime $lastUpdate): Collect
    {
        $this->lastUpdate = $lastUpdate;
        return $this;
    }

    /**
     * @return JobCollection
     */
    public function getPendingJobs(): JobCollection
    {
        return $this->pendingJobs;
    }

    public function getRealTimeData(): RealTimeDataCollection
    {
        return $this->realTimeData;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $data = $this->toArray();
        $data['errors'] = array_values($data['errors']);
        return $data;
    }
}
