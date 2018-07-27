<?php

namespace DataSourceBundle\Aws\CostExplorer;

use DataSourceBundle\Aws\CostExplorer\Parser\CostDaily;
use DataSourceBundle\Aws\CostExplorer\Parser\ResultSet;
use DataSourceBundle\Aws\CostExplorer\Parser\ServiceCost;
use DataSourceBundle\Tests\Aws\CostExplorer\DataProvider;
use EssentialsBundle\Entity\TimePeriod\TimePeriodProvider;

class CostExplorerTest extends \PHPUnit_Framework_TestCase
{
    use DataProvider;

    /**
     * @var CostExplorer
     */
    protected $costExplorer;

    /**
     * @var TimePeriodProvider
     */
    protected $timePeriod;

    protected function setUp()
    {
        parent::setUp();
        $this->timePeriod = self::createTimePeriod();
        $this->costExplorer = $this->getMockBuilder(CostExplorer::class)
            ->setConstructorArgs(['key', 'secret', $this->timePeriod])
            ->setMethods(['doRequest'])
            ->getMock();
    }

    /**
     * @testdox
     */
    public function getCost()
    {
       $this->costExplorer->method('doRequest')->willReturn($this->dailyCostResult);
       $this->assertInstanceOf(ResultSet::class, $this->costExplorer->getCost(GranularityEnum::DAILY));
       $this->assertEquals(GranularityEnum::DAILY, $this->costExplorer->getConfig()->getGranularity());
       $this->assertInstanceOf(CostDaily::class, $this->costExplorer->getParser());
       $this->assertEquals($this->timePeriod, $this->costExplorer->getTimePeriod());
    }

    /**
     * @testdox
     */
    public function getCostByService()
    {
        $this->costExplorer->method('doRequest')->willReturn($this->serviceCostResult);
        $this->assertInstanceOf(ResultSet::class, $this->costExplorer->getCostByService());
        $this->assertTrue($this->costExplorer->getConfig()->isGroupedByService());
        $this->assertInstanceOf(ServiceCost::class, $this->costExplorer->getParser());
    }
}
