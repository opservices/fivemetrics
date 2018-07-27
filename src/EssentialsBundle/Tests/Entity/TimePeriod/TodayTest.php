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
use EssentialsBundle\Entity\TimePeriod\Today;

class TodayTest extends TimePeriodTest
{

    protected function setUp()
    {
        $this->timePeriod = new Today();
    }

    /**
     * @testdox Verify whether current tests are being executed on TimPeriodToday class.
     * @test
     */
    public function isAnInstanceOfTimePeriodToday()
    {
        $this->assertInstanceOf(Today::class, $this->timePeriod);
    }

    /**
     * @testdox Compare whether current date(time) is equals to returned by Today class on relational format.
     * @test
     */
    public function getTimeRelationalFormat()
    {
        $format = TimePeriodInterface::RELATIONAL_FORMAT;
        $date = new DateTime();
        do {
            $date->modify("now");
            $timePeriod = new Today();
        } while ($date->format("U") != $timePeriod->getEnd("U"));

        $this->assertEquals(
            $date->format($format),
            $timePeriod->getEnd($format)
        );

        $str = $date->format('Y-m-d 00:00:00P');
        $date = DateTime::createFromFormat(
            'Y-m-d H:i:sP',
            $str
        );

        $this->assertEquals(
            $date->format($format),
            $timePeriod->getStart($format)
        );
    }

    /**
     * @testdox Compare whether current date(time) is equals to returned by Today class on nosql format.
     * @test
     */
    public function getTimeNoSqlFormat()
    {
        $format = TimePeriodInterface::NO_SQL_FORMAT;
        $date = new DateTime();
        do {
            $date->modify("now");
            $timePeriod = new Today();
        } while ($date->format("U") != $timePeriod->getEnd("U"));

        $this->assertEquals(
            $date->format($format),
            $timePeriod->getEnd($format)
        );

        $str = $date->format('Y-m-d 00:00:00P');
        $date = DateTime::createFromFormat(
            'Y-m-d H:i:sP',
            $str
        );

        $this->assertEquals(
            $date->format($format),
            $timePeriod->getStart($format)
        );
    }
}
