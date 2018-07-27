<?php

namespace DataSourceBundle\Aws\CostExplorer;

use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\Entity\TimePeriod\TimePeriodProvider;

class ConfigTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Config
     */
    private $config = null;

    public function setUp()
    {
        parent::setUp();
        $this->config = new Config();
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->config = null;
    }

    /**
     * @testdox Should return the only default values
     */
    public function checkDefaultValues()
    {
        $this->assertEquals([
            'Granularity' => 'MONTHLY',
            'Metrics' => ['UnblendedCost']
        ], $this->config->toArray());
    }

    /**
     * @testdox Should set an aws supported granularity
     * @dataProvider granularity
     */
    public function setGranularity($expected, $granularity)
    {
        $this->config->setGranularity($granularity);
        $this->assertEquals($expected, $this->config->getGranularity());
    }

    public function granularity()
    {
        return [['DAILY', 'DAILY'], ['MONTHLY', 'MONTHLY'], ['MONTHLY', 'bla']];
    }

    /**
     * @testdox Should append GroupBy Key and set it to SERVICE
     */
    public function groupByService()
    {
        $this->config->groupByService();
        $this->assertTrue($this->config->isGroupedByService());
    }

    /**
     * @testdox Should merge a given array to the actual configuration
     */
    public function merge()
    {
        $this->config->merge(['Test' => 'Test']);
        $this->assertEquals([
            'Granularity' => 'MONTHLY',
            'Metrics' => ['UnblendedCost'],
            'Test' => 'Test'
        ], $this->config->toArray());
    }

    /**
     * @testdox Should set an appropriate timeperiod
     */
    public function setTimePeriod()
    {
        $start = new DateTime('first day of this month');
        $end = new DateTime('last day of this month');
        $this->config->setTimePeriod((new TimePeriodProvider)->getCustomTimePeriod($start, $end));
        $result = $this->config->toArray();
        $format = $this->config::DATE_FORMAT;
        $this->assertEquals($start->format($format), $result['TimePeriod']['Start']);
        $this->assertEquals($end->format($format), $result['TimePeriod']['End']);
    }
}
