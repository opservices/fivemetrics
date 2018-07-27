<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 5/24/17
 * Time: 12:01 PM
 */

namespace EssentialsBundle\Tests\Entity\TimePeriod;

use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\Entity\TimePeriod\LastYear;
use EssentialsBundle\Entity\TimePeriod\TimePeriodInterface;

class LastYearTest extends TimePeriodTest
{

    protected function setUp()
    {
        $this->timePeriod = new LastYear();
    }

    /**
     * @testdox Verify whether current tests are being executed on TimPeriodLastYear class.
     * @test
     */
    public function isAnInstanceOfTimePeriodLastYear()
    {
        $this->assertInstanceOf(LastYear::class, $this->timePeriod);
    }

    /**
     * @testdox Compare whether current date(time) is equals to returned by LastYear class on relational format.
     * @test
     */
    public function getTimeRelationalFormat()
    {
        $format = TimePeriodInterface::RELATIONAL_FORMAT;
        $date = new DateTime();
        $date->modify("-1 year");
        $str = $date->format('Y-01-01 00:00:00P');
        $date = DateTime::createFromFormat(
            'Y-m-d H:i:sP',
            $str
        );

        $this->assertEquals(
            $this->timePeriod->getStart($format),
            $date->format($format)
        );

        $str = $date->format('Y-12-31 23:59:59P');
        $date = DateTime::createFromFormat(
            'Y-m-d H:i:sP',
            $str
        );

        $this->assertEquals(
            $date->format($format),
            $this->timePeriod->getEnd($format)
        );
    }

    /**
     * @testdox Compare whether current date(time) is equals to returned by LastYear class on nosql format.
     * @test
     */
    public function getTimeNoSqlFormat()
    {
        $format = TimePeriodInterface::NO_SQL_FORMAT;
        $date = new DateTime();
        $date->modify("-1 year");
        $str = $date->format('Y-01-01 00:00:00P');
        $date = DateTime::createFromFormat(
            'Y-m-d H:i:sP',
            $str
        );

        $this->assertEquals(
            $this->timePeriod->getStart($format),
            $date->format($format)
        );

        $str = $date->format('Y-12-31 23:59:59P');
        $date = DateTime::createFromFormat(
            'Y-m-d H:i:sP',
            $str
        );

        $this->assertEquals(
            $date->format($format),
            $this->timePeriod->getEnd($format)
        );
    }
}
