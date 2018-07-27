<?php

namespace DataSourceBundle\Aws\CostExplorer\Parser;

use DataSourceBundle\Tests\Aws\CostExplorer\DataProvider;
use EssentialsBundle\Entity\DateTime\DateTime;

class CostTest extends \PHPUnit_Framework_TestCase
{
    use DataProvider;

    const FORMAT = 'Y-m-d';
    const AMOUNT = 313.0164395738;
    const CURRENCY = 'USD';
    const FORECAST = 535.1571386261743;

    protected static $timeperiod;
    protected static $fakeNow;


    public static function setUpBeforeClass()
    {
        self::$timeperiod = self::createTimePeriod(self::FORMAT);
        self::$fakeNow = DateTime::createFromFormat(self::FORMAT, '2018-07-10');
    }

    public static function tearDownAfterClass()
    {
        self::$timeperiod = null;
        self::$fakeNow = null;
    }

    /**
     * @testdox
     */
    public function parseMontlyCost()
    {
        $parser = new CostMonthly(self::$timeperiod, self::$fakeNow);
        $result = $parser->parse($this->monthlyCostResult)->toArray();
        $this->assertSame(self::AMOUNT, $result['amount']);
        $this->assertEquals(self::CURRENCY, $result['currency']);
        $this->assertSame(self::FORECAST, $result['forecast']);
    }

    /**
     * @testdox
     */
    public function parseDailyCost()
    {
        $parser = new CostDaily(self::$timeperiod, self::$fakeNow);
        $result = $parser->parse($this->dailyCostResult)->toArray();
        $firstPoint = $result['points'][0];

        $this->assertSame(self::AMOUNT, $result['amount']);
        $this->assertEquals(self::CURRENCY, $result['currency']);
        $this->assertEquals(self::FORECAST, $result['forecast']);
        $this->assertCount(30, $result['points']);

        $this->assertInstanceOf(DateTime::class, $firstPoint['time']);
        $this->assertEquals(253.0696701324, $firstPoint['amount']);
        $this->assertEquals(self::CURRENCY, $firstPoint['currency']);
    }

    /**
     * @testdox
     */
    public function parseServiceCost()
    {
        $parser = new ServiceCost();
        $result = $parser->parse($this->serviceCostResult)->toArray();
        $firstPoint = $result['points'][0];

        $this->assertSame(self::AMOUNT, $result['amount']);
        $this->assertCount(14, $result['points']);

        $this->assertEquals('Amazon Elastic Compute Cloud - Compute', $firstPoint['service']);
        $this->assertEquals(251.9023191242, $firstPoint['amount']);
        $this->assertEquals(self::CURRENCY, $firstPoint['currency']);
    }
}
