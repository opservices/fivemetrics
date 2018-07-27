<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 28/11/17
 * Time: 09:41
 */

namespace EssentialsBundle\Profiler\Analyzer;

use EssentialsBundle\Collection\Metric\MetricCollection;
use EssentialsBundle\Collection\Metric\PointCollection;
use EssentialsBundle\Collection\Tag\TagCollection;
use EssentialsBundle\Entity\Metric\Metric;
use EssentialsBundle\Entity\Metric\Point;
use EssentialsBundle\Profiler\Profiler;

class Job extends AnalyzerAbstract
{
    private const METRICS = [
        [
            'name' => 'job.time.wait',
            'method' => 'getWaitTime',
        ],
        [
            'name' => 'job.time.lifetime',
            'method' => 'getLifetime',
        ],
        [
            'name' => 'job.time.processing',
            'method' => 'getProcessingTime',
        ],
    ];

    /**
     * @inheritdoc
     */
    protected function analyze(
        TagCollection $tags,
        MetricCollection $metrics
    ): MetricCollection {
        $profiler = $this->getProfiler();

        $tags->concat($profiler->getTags());

        foreach (self::METRICS as $metric) {
            $metrics->add($this->getMetricInstance(
                $tags,
                $metric['name'],
                $profiler,
                $metric['method']
            ));
        }

        return $metrics;
    }

    /**
     * @param TagCollection $tags
     * @param string $metricName
     * @param Profiler $profiler
     * @param string $method
     * @return Metric
     */
    protected function getMetricInstance(
        TagCollection $tags,
        string $metricName,
        Profiler $profiler,
        string $method
    ): Metric {
        $value = $profiler->$method();

        return new Metric(
            $metricName,
            $tags,
            new PointCollection([ new Point(
                (is_null($value)) ? 0 : $value,
                null,
                null,
                null,
                null,
                null,
                's'
            ) ])
        );
    }
}
