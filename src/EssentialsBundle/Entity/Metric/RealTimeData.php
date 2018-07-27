<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 16/04/18
 * Time: 13:58
 */

namespace EssentialsBundle\Entity\Metric;

use EssentialsBundle\Entity\EntityAbstract;

class RealTimeData extends EntityAbstract
{
    const CACHE_KEY_PREFIX = 'realtime';

    const DEFAULT_CACHE_LIFETIME = 172800; // 48 Hours

    /**
     * @var string
     */
    protected $metricName;

    /**
     * @var mixed
     */
    protected $data;

    /**
     * @var string
     */
    protected $suffix;

    /**
     * @var int
     */
    protected $lifeTime;

    /**
     * RealTimeData constructor.
     * @param string $metricName
     * @param null $data
     * @param string|null $suffix
     * @param int $lifeTime
     */
    public function __construct(
        string $metricName,
        $data = null,
        string $suffix = null,
        int $lifeTime = self::DEFAULT_CACHE_LIFETIME
    ) {
        $this->setMetricName($metricName)
            ->setData($data);
        $this->suffix = $suffix;
        $this->lifeTime = $lifeTime;
    }

    /**
     * @return int
     */
    public function getLifeTime(): int
    {
        return $this->lifeTime;
    }

    /**
     * @return mixed
     */
    public function getSuffix()
    {
        return $this->suffix;
    }

    /**
     * @param bool $includeSuffix
     * @return string
     */
    public function getRealTimeKey(): string
    {
        return $this->buildKey(true);
    }

    public function getReferenceKey(): string
    {
        return $this->buildKey(false);
    }

    protected function buildKey(bool $includeSuffix): string
    {
        $keyParts = [ RealTimeData::CACHE_KEY_PREFIX, $this->getMetricName() ];

        if (($includeSuffix) && ($this->getSuffix())) {
            $keyParts[] = $this->getSuffix();
        }

        return implode('-', $keyParts);
    }

    /**
     * @return bool
     */
    public function isNeedReference(): bool
    {
        $dataKey = $this->buildKey(true);
        $refKey = $this->buildKey(false);

        return ($dataKey != $refKey);
    }

    /**
     * @return string
     */
    public function getMutexKeyName(): string
    {
        return implode('-', [
            RealTimeData::CACHE_KEY_PREFIX,
            $this->getMetricName(),
            'mutex'
        ]);
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
     * @return RealTimeData
     */
    public function setMetricName(string $metricName): RealTimeData
    {
        // to validate metric name.
        (new Metric($metricName));

        $this->metricName = $metricName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     * @return RealTimeData
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }
}
