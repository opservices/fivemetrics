<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 7/13/17
 * Time: 10:30 AM
 */

namespace DataSourceBundle\Tests\Gearman\Queue\Collector\Generic\Processor\Aws\S3\Filter;

use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\S3\FilterCollection;
use DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\S3\Filter;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\S3\Filter\GroupingResolver;
use PHPUnit\Framework\TestCase;

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
        foreach (GroupingResolver::MEASUREMENTS as $measurement) {
            $filters->add(new Filter('S3', $measurement));
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
                'S3',
                $measurements
            )
        ]);

        $rightFilters = new FilterCollection();
        foreach (GroupingResolver::MEASUREMENTS as $measurement) {
            $rightFilters->add(new Filter('S3', $measurement));
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
                'S3',
                [ 'junk' ]
            )
        ]);
        $result = $this->groupResolver->splitMeasurements($filters);

        $this->assertEquals(0, count($result));
    }
}
