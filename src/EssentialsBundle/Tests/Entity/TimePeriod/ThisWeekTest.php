<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 5/25/17
 * Time: 8:53 AM
 */

namespace EssentialsBundle\Tests\Entity\TimePeriod;

use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\Entity\TimePeriod\TimePeriodInterface;
use EssentialsBundle\Entity\TimePeriod\ThisWeek;

class ThisWeekTest extends TimePeriodTest
{
    protected function setUp()
    {
        $this->timePeriod = new ThisWeek;
    }

    /**
     * @testdox Verify whether current tests are being executed on TimPeriodThisWeek class.
     * @test
     */
    public function isAnInstanceOfTimePeriodThisWeek()
    {
        $this->assertInstanceOf(ThisWeek::class, $this->timePeriod);
    }

    /**
     * @testdox Compare whether current date(time) is equals to returned by ThisWeek class on relational format.
     * @test
     */
    public function getTimeRelationalFormat()
    {
        $format = TimePeriodInterface::RELATIONAL_FORMAT;
        $date = new DateTime('now', new \DateTimeZone('UTC'));
        do {
            $date->modify("now");
            $timePeriod = new ThisWeek($date);
        } while ($date->format("U") != $timePeriod->getEnd("U"));

        $this->assertEquals(
            $date->format($format),
            $timePeriod->getEnd($format)
        );

        $date->modify("sunday last week");
        $this->assertEquals(
            $timePeriod->getStart($format),
            $date->format($format)
        );
    }

    /**
     * @testdox Compare whether current date(time) is equals to returned by ThisWeek class on nosql format.
     * @test
     */
    public function getTimeNoSqlFormat()
    {
        $format = TimePeriodInterface::NO_SQL_FORMAT;
        $date = new DateTime('now', new \DateTimeZone('UTC'));
        do {
            $date->modify("now");
            $timePeriod = new ThisWeek($date);
        } while ($date->format("U") != $timePeriod->getEnd("U"));

        $this->assertEquals(
            $date->format($format),
            $timePeriod->getEnd($format)
        );

        $date->modify("sunday last week");
        $this->assertEquals(
            $timePeriod->getStart($format),
            $date->format($format)
        );
    }
}
