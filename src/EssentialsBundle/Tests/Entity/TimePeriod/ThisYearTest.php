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
use EssentialsBundle\Entity\TimePeriod\ThisYear;

class ThisYearTest extends TimePeriodTest
{
    protected function setUp()
    {
        $this->timePeriod = new ThisYear();
    }

    /**
     * @testdox Verify whether current tests are being executed on TimPeriodThisYear class.
     * @test
     */
    public function isAnInstanceOfTimePeriodThisYear()
    {
        $this->assertInstanceOf(ThisYear::class, $this->timePeriod);
    }

    /**
     * @testdox Compare whether current date(time) is equals to returned by ThisYear class on relational format.
     * @test
     */
    public function getTimeRelationalFormat()
    {
        $format = TimePeriodInterface::RELATIONAL_FORMAT;
        $date = new DateTime();
        do {
            $date->modify("now");
            $timePeriod = new ThisYear();
        } while ($date->format("U") != $timePeriod->getEnd("U"));

        $this->assertEquals(
            $timePeriod->getEnd($format),
            $date->format($format)
        );

        $str = $date->format('Y-01-01 00:00:00P');
        $date = DateTime::createFromFormat(
            'Y-m-d H:i:sP',
            $str
        );

        $this->assertEquals(
            $timePeriod->getStart($format),
            $date->format($format)
        );
    }

    /**
     * @testdox Compare whether current date(time) is equals to returned by ThisYear class on nosql format.
     * @test
     */
    public function getTimeNoSqlFormat()
    {
        $format = TimePeriodInterface::NO_SQL_FORMAT;
        $date = new DateTime();
        do {
            $date->modify("now");
            $timePeriod = new ThisYear();
        } while ($date->format("U") != $timePeriod->getEnd("U"));

        $this->assertEquals(
            $timePeriod->getEnd($format),
            $date->format($format)
        );

        $str = $date->format('Y-01-01 00:00:00P');
        $date = DateTime::createFromFormat(
            'Y-m-d H:i:sP',
            $str
        );

        $this->assertEquals(
            $timePeriod->getStart($format),
            $date->format($format)
        );
    }
}
