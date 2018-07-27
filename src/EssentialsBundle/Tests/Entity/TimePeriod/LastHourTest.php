<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 5/24/17
 * Time: 12:01 PM
 */

namespace EssentialsBundle\Tests\Entity\TimePeriod;

use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\Entity\TimePeriod\TimePeriodInterface;
use EssentialsBundle\Entity\TimePeriod\LastHour;

class LastHourTest extends TimePeriodTest
{

    protected function setUp()
    {
        $this->timePeriod = new LastHour();
    }

    /**
     * @testdox Verify whether current tests are being executed on TimPeriodLastHour class.
     * @test
     */
    public function isAnInstanceOfTimePeriodLastWeek()
    {
        $this->assertInstanceOf(LastHour::class, $this->timePeriod);
    }

    /**
     * @testdox Compare whether current date(time) is equals to returned by LastHour class on relational format.
     * @test
     */
    public function getTimeRelationalFormat()
    {
        $format = TimePeriodInterface::RELATIONAL_FORMAT;
        $date = new DateTime();
        do {
            $date->modify("now");
            $timePeriod = new LastHour();
        } while ($date->format("U") != $timePeriod->getEnd("U"));

        $this->assertEquals(
            $date->format($format),
            $timePeriod->getEnd($format)
        );

        $date->modify("-1 hour");
        $this->assertEquals(
            $timePeriod->getStart($format),
            $date->format($format)
        );
    }

    /**
     * @testdox Compare whether current date(time) is equals to returned by LastHour class on nosql format.
     * @test
     */
    public function getTimeNoSqlFormat()
    {
        $format = TimePeriodInterface::NO_SQL_FORMAT;
        $date = new DateTime();
        do {
            $date->modify("now");
            $timePeriod = new LastHour();
        } while ($date->format("U") != $timePeriod->getEnd("U"));

        $this->assertEquals(
            $date->format($format),
            $timePeriod->getEnd($format)
        );

        $date->modify("-1 hour");
        $this->assertEquals(
            $timePeriod->getStart($format),
            $date->format($format)
        );
    }
}
