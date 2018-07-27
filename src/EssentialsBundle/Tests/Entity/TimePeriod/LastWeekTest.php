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
use EssentialsBundle\Entity\TimePeriod\LastWeek;

class LastWeekTest extends TimePeriodTest
{

    protected function setUp()
    {
        $this->timePeriod = new LastWeek(
            new DateTime('now', new \DateTimeZone('UTC'))
        );
    }

    /**
     * @testdox Verify whether current tests are being executed on TimPeriodLastWeek class.
     * @test
     */
    public function isAnInstanceOfTimePeriodLastWeek()
    {
        $this->assertInstanceOf(LastWeek::class, $this->timePeriod);
    }

    /**
     * @testdox Compare whether current date(time) is equals to returned by LastWeek class on relational format.
     * @test
     */
    public function getTimeRelationalFormat()
    {
        $format = TimePeriodInterface::RELATIONAL_FORMAT;
        $date = new DateTime();

        $date->modify("-1 week");
        $date->modify("last sunday");
        $date->setTime(00, 00, 00);

        $this->assertEquals(
            $this->timePeriod->getStart($format),
            $date->format($format)
        );

        $date->modify("+6 day");
        $date->setTime(23, 59, 59);

        $this->assertEquals(
            $date->format($format),
            $this->timePeriod->getEnd($format)
        );
    }

    /**
     * @testdox Compare whether current date(time) is equals to returned by LastWeek class on nosql format.
     * @test
     */
    public function getTimeNoSqlFormat()
    {
        $format = TimePeriodInterface::NO_SQL_FORMAT;
        $date = new DateTime('now', new \DateTimeZone('UTC'));

        $date->modify("-1 week");
        $date->modify("last sunday");
        $date->setTime(00, 00, 00);

        $this->assertEquals(
            $this->timePeriod->getStart($format),
            $date->format($format)
        );

        $date->modify("+6 day");
        $date->setTime(23, 59, 59);

        $this->assertEquals(
            $date->format($format),
            $this->timePeriod->getEnd($format)
        );
    }
}
