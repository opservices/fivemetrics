<?php

namespace DatabaseBundle\RealTime;

use Doctrine\Common\Cache\CacheProvider;
use EssentialsBundle\Entity\Metric\RealTimeData;

class Storage
{
    /**
     * @var CacheProvider
     */
    protected $cache;

    /**
     * @var array
     */
    protected $referenceKeys;

    /**
     * Storage constructor.
     * @param CacheProvider $cache
     */
    public function __construct(CacheProvider $cache)
    {
        $this->cache = $cache;
        $this->referenceKeys = [];
    }

    public function persist(RealTimeData $data)
    {
        if ($data->isNeedReference()) {
            $this->addReference($data);
        }

        $this->save($data);
    }

    public function flush()
    {
        $this->updateReferenceKeys();
    }

    protected function updateReferenceKeys()
    {
        foreach ($this->referenceKeys as $key => $value) {
            $cacheData = $this->cache->fetch($key);
            $this->saveReferences($cacheData, $key, $value);
        }
    }

    protected function saveReferences($cacheData, $key, $value): void
    {
        $updatedKeys = $this->filterValidKeys($cacheData, $key);

        $this->cache->save(
            $key,
            $this->arrayUnion(($updatedKeys[$key]) ?? [], $value),
            RealTimeData::DEFAULT_CACHE_LIFETIME
        );
    }

    /**
     * @param RealTimeData $data
     * @return bool
     */
    protected function save(RealTimeData $data)
    {
        return $this->cache->save(
            $data->getRealTimeKey(),
            $data->getData(),
            $data->getLifeTime()
        );
    }

    protected function arrayUnion(...$arr)
    {
        return array_unique(
            call_user_func_array('array_merge', $arr)
        );
    }

    /**
     * @param RealTimeData $data
     * @return Storage
     */
    protected function addReference(RealTimeData $data): Storage
    {
        $dataKey = $data->getRealTimeKey();
        $refKey = $data->getReferenceKey();

        $this->referenceKeys[$refKey][] = $dataKey;

        return $this;
    }

    protected function filterValidKeys($cacheData, $key, array $updatedKeys = []): array
    {
        foreach ($cacheData as $ref) {
            if ($this->cache->fetch($ref)) {
                $updatedKeys[$key][] = $ref;
            }
        }

        return $updatedKeys;
    }


    public function fetch(string $metricName = 'aws.ec2.reserves', array $filters = [])
    {
        if (empty($filters)) {
            return $this->fetchAll($metricName);
        }

        $resultset = $this->cache->fetchMultiple($this->fetchReferencesKeys($metricName));
        return $this->fetchByFilter($resultset, $filters);
    }

    /**
     * @param string $metricName
     *
     * @return array
     */
    protected function fetchAll(string $metricName): array
    {
        return $this->cache->fetchMultiple($this->fetchReferencesKeys($metricName));
    }

    /**
     * @param string $metricName
     * @return array
     */
    protected function fetchReferencesKeys(string $metricName): array
    {
        $result = $this->cache->fetch(RealTimeData::CACHE_KEY_PREFIX . '-' . $metricName);
        return is_array($result) ? $result : [];
    }

    protected function fetchByFilter(array $resultset, array $filters = []): array
    {
        $result = [];
        foreach ($resultset as $key => $metrics) {
            $result[$key] = $this->applyFilter($metrics, $filters);
        }

        return $result;
    }

    protected function applyFilter(array $metrics, array $filters)
    {
        return array_filter($metrics, function ($item) use ($filters) {
            $item = array_change_key_case($item, CASE_LOWER);
            foreach ($filters as $fieldName => $value) {
                if ($item["$fieldName"] != $value && ! in_array($item["$fieldName"], $value)) {
                    return false;
                }
            }

            return true;
        });
    }
}
