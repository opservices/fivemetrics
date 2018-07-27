<?php

namespace DataSourceBundle\Aws\CostExplorer;


use DataSourceBundle\Aws\CostExplorer\Parser\CostDaily;
use DataSourceBundle\Aws\CostExplorer\Parser\CostMonthly;
use DataSourceBundle\Aws\CostExplorer\Parser\RawCost;
use DataSourceBundle\Aws\CostExplorer\Parser\ServiceCost;
use DataSourceBundle\Aws\CostExplorer\Parser\TypeEnum;
use DataSourceBundle\Tests\Aws\CostExplorer\DataProvider;
use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\Entity\TimePeriod\TimePeriodProvider;

class ParserFactoryTest extends \PHPUnit_Framework_TestCase
{
    use DataProvider;
    /**
     * @testdox Should create an appropriate object based on passed parameters
     */
    public function create()
    {
        $factory = new ParserFactory();
        $timePeriod = self::createTimePeriod();
        $this->assertInstanceOf(CostMonthly::class, $factory->create($timePeriod));
        $this->assertInstanceOf(CostDaily::class, $factory->create($timePeriod, GranularityEnum::DAILY));
        $this->assertInstanceOf(ServiceCost::class, $factory->create());
    }

}
