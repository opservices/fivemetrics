<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 5/29/17
 * Time: 4:08 PM
 */

namespace EssentialsBundle\Tests\Entity\TimePeriod;

use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\Entity\TimePeriod\Custom;
use EssentialsBundle\Entity\TimePeriod\TimePeriodInterface;
use EssentialsBundle\Entity\TimePeriod\TimePeriodProvider;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

class TimePeriodProviderTest extends TestCase
{
    /**
     * @var TimePeriodProvider
     */
    protected $timePeriodProvider;
    public function setUp()
    {
        $this->timePeriodProvider = new TimePeriodProvider();
    }

    /**
     * @testdox Verify if an exception is launched if timeperiod not exists
     * @dataProvider invalidTimePeriods
     * @expectedException \InvalidArgumentException
     * @param $timePeriod
     */
    public function getTimePeriodFromInvalidTimePeriodName($timePeriod)
    {
        $this->timePeriodProvider->factory($timePeriod);
    }

    /**
     * @testdox Verify if an timePeriod solicited is an instance of TimePeriodInterface
     * @dataProvider validTimePeriods
     * @param $timePeriod
     */
    public function getTimePeriodFromValidTimePeriodName($timePeriod)
    {
        $timePeriod = $this->timePeriodProvider->factory($timePeriod);
        $this->assertInstanceOf(TimePeriodInterface::class, $timePeriod);
    }

    /**
     * @testdox Verify whether function are returning a Custom timePeriod class.
     */
    public function isTimePeriodInstanceOfCustomTimePeriod()
    {
        $dateTime = new DateTime();
        $timePeriod = $this->timePeriodProvider->getCustomTimePeriod($dateTime, $dateTime);

        $this->assertInstanceOf(Custom::class, $timePeriod);
    }

    /**
     * @testdox Verify whether function are returning possibles timeperiods
     */
    public function getPossibleTimePeriods()
    {
        $expectedPossibleTimePeriods = [
            "lastminute",
            "last5minutes",
            "last10minutes",
            "last15minutes",
            "last30minutes",
            "last7days",
            "last24hours",
            "last30days",
            "last31days",
            "lasthour",
            "lastmonth",
            "lastweek",
            "lastyear",
            "last15days",
            "thishour",
            "thismonth",
            "thisweek",
            "thisyear",
            "today",
            "yesterday",
        ];

        $possibleTimePeriods = $this->timePeriodProvider->listTimePeriods();
        $this->assertEquals($expectedPossibleTimePeriods, array_values($possibleTimePeriods));
    }

    public function invalidTimePeriods()
    {
        return [
            ["thistime"],
            ["15minutesago"],
            ["nextHour"]
        ];
    }

    public function validTimePeriods()
    {
        return [
            ["last7days"],
            ["last24hours"],
            ["last31days"],
            ["last30days"],
            ["lasthour"],
            ["lastmonth"],
            ["lastweek"],
            ["lastyear"],
            ["last15days"],
            ["lastminute"],
            ["last5minutes"],
            ["thishour"],
            ["thismonth"],
            ["thisweek"],
            ["thisyear"],
            ["today"],
            ["yesterday"],
        ];
    }
}
