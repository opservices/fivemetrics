<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 8/7/17
 * Time: 3:14 PM
 */

namespace DataSourceBundle\Tests\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier\Filter;

use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier\FilterCollection;
use DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier\Filter;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier\Filter\GroupingResolver;
use PHPUnit\Framework\TestCase;

/**
 * Class GroupingResolverTest
 * @package DataSourceBundle\Tests\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier\Filter
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
        foreach (GroupingResolver::MEASUREMENTS as $measurement) {
            $filters->add(new Filter('Glacier', $measurement));
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
        $measurements[] = 'junk';

        $filters = new FilterCollection([
            new Filter(
                'Glacier',
                $measurements
            )
        ]);

        $rightFilters = new FilterCollection();
        foreach (GroupingResolver::MEASUREMENTS as $measurement) {
            $rightFilters->add(new Filter('Glacier', $measurement));
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
                'Glacier',
                [ 'junk' ]
            )
        ]);
        $result = $this->groupResolver->splitMeasurements($filters);

        $this->assertEquals(0, count($result));
    }
}
