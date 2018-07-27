<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 8/16/17
 * Time: 3:45 PM
 */

namespace DataSourceBundle\Tests\Gearman\Queue\Collector\Generic\Processor\Aws\EBS\Filter;

use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\EBS\FilterCollection;
use DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\EBS\Filter;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EBS\Filter\GroupingResolver;
use PHPUnit\Framework\TestCase;

/**
 * Class GroupingResolverTest
 * @package DataSourceBundle\Tests\Gearman\Queue\Collector\Generic\Processor\Aws\EBS\Filter
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
            $filters->add(new Filter('EBS', $measurementGroup));
        }

        $this->assertEquals(
            $filters,
            $this->groupResolver->splitMeasurements()
        );
    }

    /**
     * @test
     */
    public function splitMeasurements()
    {
        $measurements = [];
        foreach (GroupingResolver::MEASUREMENTS as $measurement) {
            $measurements = array_merge($measurements, $measurement);
        }

        $filters = new FilterCollection([
            new Filter(
                'EBS',
                $measurements
            )
        ]);

        $rightFilters = new FilterCollection();
        foreach (GroupingResolver::MEASUREMENTS as $measurement) {
            $rightFilters->add(new Filter('EBS', $measurement));
        }

        $this->assertEquals(
            $rightFilters,
            $this->groupResolver->splitMeasurements($filters)
        );
    }

    /**
     * @test
     */
    public function splitOnlyInvalidMeasurements()
    {
        $filters = new FilterCollection([
            new Filter(
                'EBS',
                [ 'junk' ]
            )
        ]);
        $result = $this->groupResolver->splitMeasurements($filters);

        $this->assertEquals(0, count($result));
    }
}
