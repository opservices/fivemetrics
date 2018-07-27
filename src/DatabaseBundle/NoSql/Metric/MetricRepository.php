<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 25/05/17
 * Time: 09:47
 */

namespace DatabaseBundle\NoSql\Metric;

use DatabaseBundle\NoSql\DatabaseConnectionProvider;
use DatabaseBundle\NoSql\QueryBuilder\QueryBuilder;
use EssentialsBundle\Collection\Metric\MetricCollection;
use EssentialsBundle\Entity\Metric\Builder;
use EssentialsBundle\Entity\Metric\Point;
use EssentialsBundle\Reflection;
use InfluxDB\Database;
use ReflectionProperty;

/**
 * Class MetricRepository
 * @package NoSql\Metric
 */
class MetricRepository
{
    const POINTS_LIMIT = 1000;

    /**
     * @var QueryBuilder
     */
    protected $queryBuilder;

    /**
     * @var DatabaseConnectionProvider
     */
    protected $connectionProvider;

    /**
     * MetricRepository constructor.
     * @param QueryBuilder|null $builder
     * @param DatabaseConnectionProvider|null $provider
     */
    public function __construct(
        QueryBuilder $builder = null,
        DatabaseConnectionProvider $provider = null
    ) {
        $this->queryBuilder = (is_null($builder))
            ? new QueryBuilder()
            : $builder;

        $this->connectionProvider = (is_null($provider))
            ? new DatabaseConnectionProvider()
            : $provider;
    }

    /**
     * @param string $databaseId
     * @param string $metricName
     * @param array $params
     * @return array
     */
    public function getHistory(
        string $databaseId,
        string $metricName,
        array $params
    ): array {
        $series = $this->getInfluxSeriesData(
            $databaseId,
            $metricName,
            $params
        );

        $responseData = [];
        foreach ($series as $serie) {
            $tags = [];

            if (isset($serie['tags'])) {
                foreach ($serie['tags'] as $key => $value) {
                    $key = urldecode($key);
                    $value = urldecode($value);
                    $tags[$key] = $value;
                }
            }

            $responseData[] = array_merge(
                [
                    'name' => $serie['name'],
                    'tags' => $tags,
                ],
                $this->buildResponsePoints(
                    $serie['columns'],
                    $serie['values']
                )
            );
        }

        return [
            'series' => $responseData,
        ];
    }

    /**
     * @param string $databaseId
     * @param string $metricName
     * @param array $params
     * @return array
     */
    protected function getInfluxSeriesData(
        string $databaseId,
        string $metricName,
        array $params
    ) {
        return $this->connectionProvider->getConnection($databaseId)
            ->getClient()
            ->query($databaseId, $this->getInfluxQL($metricName, $params))
            ->getSeries();
    }

    /**
     * @param string $metricName
     * @param array $params
     * @return string
     */
    protected function getInfluxQL(string $metricName, array $params): string
    {
        return $this->queryBuilder->getQuery($params, $metricName);
    }

    /**
     * @param array $key
     * @param array $values
     * @return array
     */
    protected function buildResponsePoints(array $key, array $values): array
    {
        $points = [];
        $max = null;
        $min = null;

        foreach ($values as $value) {
            $point = array_combine($key, $value);

            $max = (is_null($max)) ? $point['value'] : max($max, $point['value']);
            $min = (is_null($min)) ? $point['value'] : min($min, $point['value']);

            $points[] = $point;
        }

        return [
            'points' => $points,
            'minimum' => $min,
            'maximum' => $max,
        ];
    }

    /**
     * @param string $databaseId
     * @param MetricCollection $metrics
     * @return MetricRepository
     */
    public function putMetrics(
        string $databaseId,
        MetricCollection $metrics
    ): MetricRepository {
        $database = $this->connectionProvider->getConnection($databaseId);

        foreach ($metrics as $metric) {
            $chunks = array_chunk($metric->toInfluxPoints(), 50, true);
            foreach ($chunks as $chunk) {
                $database->writePoints($chunk, Database::PRECISION_SECONDS);
            }
        }

        unset($database);

        return $this;
    }

    public function influxPointsToMetrics(
        array $points,
        string $measurement
    ): MetricCollection {
        $pointKeys = Reflection::getObjectProperties(
            Point::class,
            ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED
        );

        $metricsData = array_map(function ($point) use ($measurement, $pointKeys) {
            $data = ['name' => $measurement, 'points' => []];

            $data['points'][] = array_filter($point, function ($key) use ($pointKeys) {
                return (in_array($key, $pointKeys));
            }, ARRAY_FILTER_USE_KEY);

            foreach ($point as $key => $value) {
                if (in_array($key, $pointKeys)) {
                    continue;
                }

                $data['tags'][] = ['key' => $key, 'value' => $value];
            }

            return $data;
        }, $points);

        return Builder::build($metricsData);
    }
}
