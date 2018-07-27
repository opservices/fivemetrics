<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 28/11/17
 * Time: 09:33
 */

namespace EssentialsBundle\Profiler\Analyzer;

use EssentialsBundle\Collection\Metric\MetricCollection;
use EssentialsBundle\Collection\Tag\TagCollection;
use EssentialsBundle\Profiler\Profiler;

interface AnalyzerInterface
{
    /**
     * @return mixed
     */
    public function getProfiler();

    /**
     * @param Profiler $profiler
     * @return AnalyzerInterface
     */
    public function setProfiler(Profiler $profiler): AnalyzerInterface;

    /**
     * @param TagCollection|null $tags
     * @return MetricCollection
     */
    public function getMetrics(TagCollection $tags = null): MetricCollection;
}
