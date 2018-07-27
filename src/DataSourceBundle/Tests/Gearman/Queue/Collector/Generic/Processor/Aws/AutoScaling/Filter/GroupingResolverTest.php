<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 20/03/17
 * Time: 16:40
 */

namespace DataSourceBundle\Tests\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScaling\Filter;

use DataSourceBundle\Collection\Aws\AutoScaling\ActivityCollection;
use DataSourceBundle\Collection\Aws\AutoScaling\EnabledMetricCollection;
use DataSourceBundle\Collection\Aws\AutoScaling\InstanceCollection;
use DataSourceBundle\Collection\Aws\AutoScaling\SuspendedProcessCollection;
use DataSourceBundle\Collection\Aws\AutoScaling\TagCollection;
use DataSourceBundle\Collection\Aws\EC2\Instance\InstanceCollection as EC2InstaceCollection;
use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScalingGroup\FilterCollection;
use DataSourceBundle\Entity\Aws\AutoScaling\AutoScalingGroup;
use EssentialsBundle\Entity\DateTime\DateTime;
use DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScalingGroup\Filter;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScaling\Filter\GroupingResolver;
use PHPUnit\Framework\TestCase;

/**
 * Class GroupingResolverTest
 * @package DataSourceBundle\Tests\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScaling\Filter
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
            $filters->add(new Filter('AutoScaling', $measurementGroup));
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
        foreach (GroupingResolver::MEASUREMENTS as $group) {
            $measurements = array_merge($measurements, $group);
        }
        $measurements[] = 'junk';

        $filters = new FilterCollection([
            new Filter(
                'AutoScaling',
                $measurements
            )
        ]);

        $rightFilters = new FilterCollection();
        foreach (GroupingResolver::MEASUREMENTS as $measurementGroup) {
            $rightFilters->add(new Filter('AutoScaling', $measurementGroup));
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
                'AutoScaling',
                [ 'junk' ]
            )
        ]);
        $result = $this->groupResolver->splitMeasurements($filters);

        $this->assertEquals(0, count($result));
    }

    /**
     * @test
     */
    public function splitValidMeasurementsWithData()
    {
        $atg = new AutoScalingGroup(
            "autoScalingGroupName",
            "autoScalingGroupARN",
            "launchConfigurationName",
            2,
            4,
            3,
            300,
            [ "az1", "az2" ],
            [ "lb1", "lb2" ],
            [ "targetGroupARNs" ],
            "healthCheckType",
            60,
            DateTime::createFromFormat('Y-m-d H:i', '2017-02-16 14:17'),
            "VPCZoneIdentifier",
            [ "terminationPolicies" ],
            false,
            new InstanceCollection(),
            new SuspendedProcessCollection(),
            new EnabledMetricCollection(),
            new TagCollection(),
            new ActivityCollection()
        );

        $filters = new FilterCollection([
            new Filter(
                'AutoScaling',
                [ 'Instances' ],
                new EC2InstaceCollection(),
                $atg
            )
        ]);

        $this->assertEquals(
            $filters,
            $this->groupResolver->splitMeasurements($filters)
        );
    }
}
