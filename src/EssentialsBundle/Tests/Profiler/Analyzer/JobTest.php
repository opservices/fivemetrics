<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 07/12/17
 * Time: 16:22
 */

namespace EssentialsBundle\Tests\Profiler\Analyzer;

use EssentialsBundle\Collection\Tag\TagCollection;
use EssentialsBundle\Entity\Metric\Metric;
use EssentialsBundle\Entity\Tag\Tag;
use EssentialsBundle\Profiler\Analyzer\Job;
use EssentialsBundle\Profiler\Profiler;
use PHPUnit\Framework\TestCase;

class JobTest extends TestCase
{
    /**
     * @var Job
     */
    protected $analyzer;

    public function setUp()
    {
        $this->analyzer = new Job();
    }

    /**
     * @test
     */
    public function getMetrics()
    {
        $profiler = new Profiler(new TagCollection([
            new Tag('origin', 'unit.test')
        ]));

        $metrics = $this->analyzer->setProfiler($profiler)
            ->getMetrics();

        $this->assertGreaterThan(0, count($metrics));

        foreach ($metrics as $metric) {
            /** @var Metric $metric */
            $tag = $metric->getTags()->find('origin');
            /** @var Tag $tag */
            $this->assertInstanceOf(Tag::class, $tag);
            $this->assertEquals('unit.test', $tag->getValue());

            $this->assertGreaterThan(0, $metric->getPoints());
        }
    }

    /**
     * @test
     */
    public function getMetricsWithoutProfiler()
    {
        $metrics = $this->analyzer->getMetrics();
        $this->assertCount(0, $metrics);
    }
}
