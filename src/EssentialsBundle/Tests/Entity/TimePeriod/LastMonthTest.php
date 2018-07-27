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
use EssentialsBundle\Entity\TimePeriod\LastMonth;

class LastMonthTest extends TimePeriodTest
{

    protected function setUp()
    {
        $this->timePeriod = new LastMonth();
    }

    /**
     * @testdox Verify whether current tests are being executed on TimPeriodLastMouth class.
     * @test
     */
    public function isAnInstanceOfTimePeriodLastMonth()
    {
        $this->assertInstanceOf(LastMonth::class, $this->timePeriod);
    }

    /**
     * @testdox Compare whether current date(time) is equals to returned by LastMonth class on relational format.
     * @test
     */
    public function getTimeRelationalFormat()
    {
        $format = TimePeriodInterface::RELATIONAL_FORMAT;
        $date = new DateTime();
        $date->modify("-1 month");
        $str = $date->format('Y-m-01 00:00:00P');
        $date = DateTime::createFromFormat(
            'Y-m-d H:i:sP',
            $str
        );
        $this->assertEquals(
            $this->timePeriod->getStart($format),
            $date->format($format)
        );

        $date = new DateTime();
        $date->modify("-1 month");
        $date->setTime(23, 59, 59);

        $this->assertEquals(
            $date->format($format),
            $this->timePeriod->getEnd($format)
        );
    }

    /**
     * @testdox Compare whether current date(time) is equals to returned by LastMonth class on nosql format.
     * @test
     */
    public function getTimeNoSqlFormat()
    {
        $format = TimePeriodInterface::NO_SQL_FORMAT;
        $date = new DateTime();
        $date->modify("-1 month");
        $str = $date->format('Y-m-01 00:00:00P');
        $date = DateTime::createFromFormat(
            'Y-m-d H:i:sP',
            $str
        );
        $this->assertEquals(
            $this->timePeriod->getStart($format),
            $date->format($format)
        );

        $date = new DateTime();
        $date->modify("-1 month");
        $date->setTime(23, 59, 59);

        $this->assertEquals(
            $date->format($format),
            $this->timePeriod->getEnd($format)
        );
    }
}
