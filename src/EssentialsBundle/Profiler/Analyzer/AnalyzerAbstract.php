<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 28/11/17
 * Time: 11:06
 */

namespace EssentialsBundle\Profiler\Analyzer;

use EssentialsBundle\Collection\Metric\MetricCollection as Metrics;
use EssentialsBundle\Collection\Tag\TagCollection as Tags;
use EssentialsBundle\Collection\Tag\TagCollection;
use EssentialsBundle\Profiler\Profiler;

abstract class AnalyzerAbstract implements AnalyzerInterface
{
    /**
     * @var Profiler
     */
    protected $profiler = null;

    /**
     * @inheritdoc
     */
    public function getProfiler()
    {
        return $this->profiler;
    }

    /**
     * @inheritdoc
     */
    public function setProfiler(Profiler $profiler): AnalyzerInterface
    {
        $this->profiler = $profiler;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getMetrics(Tags $tags = null): Metrics
    {
        $metrics = new Metrics();

        if (! $this->getProfiler()) {
            return $metrics;
        }

        return $this->analyze($this->getTags($tags), $metrics);
    }

    /**
     * @return TagCollection
     */
    protected function getTags(TagCollection $tags = null): TagCollection
    {
        return $tags ?? new Tags();
    }

    /**
     * @param Tags $tags
     * @param Metrics $metrics
     * @return Metrics
     */
    abstract protected function analyze(Tags $tags, Metrics $metrics): Metrics;
}
