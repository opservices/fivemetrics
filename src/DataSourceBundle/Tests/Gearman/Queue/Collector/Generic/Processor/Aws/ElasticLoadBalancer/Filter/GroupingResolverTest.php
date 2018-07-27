<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 22/03/17
 * Time: 17:46
 */

namespace DataSourceBundle\Tests\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer\Filter;

use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer\FilterCollection;
use DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer\Filter;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer\Filter\GroupingResolver;
use PHPUnit\Framework\TestCase;

/**
 * Class GroupingResolverTest
 * @package DataSourceBundle\Tests\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer\Filter
 */
class GroupingResolverTest extends TestCase
{
    /**
     * @var GroupingResolver
     */
    protected $groupResolver;

    public function setUp()
    {
        $this->groupResolver = new GroupingResolver();
    }

    /**
     * @test
     */
    public function splitMeasurementsDefault()
    {
        $filters = new FilterCollection();
        foreach (GroupingResolver::MEASUREMENTS as $measurementGroup) {
            $filters->add(new Filter('ElasticLoadBalancer', $measurementGroup));
        }

        $this->assertEquals(
            $filters,
            $this->groupResolver->splitMeasurements()
        );
    }
}
