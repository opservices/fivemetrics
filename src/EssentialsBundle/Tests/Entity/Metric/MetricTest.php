<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 14/02/17
 * Time: 21:18
 */

namespace EssentialsBundle\Tests\Entity\Metric;

use EssentialsBundle\Collection\Metric\PointCollection;
use EssentialsBundle\Collection\Tag\TagCollection;
use EssentialsBundle\Entity\Metric\Metric;
use EssentialsBundle\Entity\Tag\Tag;
use PHPUnit\Framework\TestCase;

/**
 * Class MetricTest
 * @package EssentialsBundle\Test\Entity\Metric
 */
class MetricTest extends TestCase
{
    /**
     * @var Metric
     */
    protected $metric;

    public function setUp()
    {
        $this->metric = new Metric(
            'test',
            new TagCollection(),
            new PointCollection()
        );
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals('test', $this->metric->getName());

        $this->assertInstanceOf(
            "EssentialsBundle\\Collection\\Tag\\TagCollection",
            $this->metric->getTags()
        );

        $this->assertCount(0, $this->metric->getTags());

        $this->assertInstanceOf(
            "EssentialsBundle\\Collection\\Metric\\PointCollection",
            $this->metric->getPoints()
        );

        $this->assertCount(0, $this->metric->getPoints());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function addMoreTagsThanAllowed()
    {
        $tags = new TagCollection();

        for ($i = 0; $i <= Metric::MAX_ALLOWED_TAGS; $i++) {
            $tags->add(new Tag("key" . $i, "value" . $i));
        }

        $this->metric->setTags($tags);
    }

    /**
     * @test
     */
    public function instantiateMetric()
    {
        $this->assertInstanceOf(
            "EssentialsBundle\\Entity\\Metric\\Metric",
            $this->metric
        );
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @dataProvider invalidMetricNames
     */
    public function setInvalidMetricName($name)
    {
        $this->metric->setName($name);
    }

    public function invalidMetricNames()
    {
        return [
            [ '' ],
            [ ',' ]
        ];
    }
}
