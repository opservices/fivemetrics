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
use EssentialsBundle\Entity\TimePeriod\Yesterday;

class YesterdayTest extends TimePeriodTest
{

    protected function setUp()
    {
        $this->timePeriod = new Yesterday();
    }

    /**
     * @testdox Verify whether current tests are being executed on TimPeriodYesterday class.
     * @test
     */
    public function isAnInstanceOfTimePeriodYesterday()
    {
        $this->assertInstanceOf(Yesterday::class, $this->timePeriod);
    }

    /**
     * @test
     */
    public function getTimeRelationalFormat()
    {
        $format = TimePeriodInterface::RELATIONAL_FORMAT;
        $date = new DateTime();
        $date->setTime(00, 00, 00);
        $date->modify("-1 day");

        $this->assertEquals(
            $this->timePeriod->getStart($format),
            $date->format($format)
        );

        $date->setTime(23, 59, 59);
        $this->assertEquals(
            $date->format($format),
            $this->timePeriod->getEnd($format)
        );
    }

    /**
     * @test
     */
    public function getTimeNoSqlFormat()
    {
        $format = TimePeriodInterface::NO_SQL_FORMAT;
        $date = new DateTime();
        $date->setTime(00, 00, 00);
        $date->modify("-1 day");

        $this->assertEquals(
            $this->timePeriod->getStart($format),
            $date->format($format)
        );

        $date->setTime(23, 59, 59);
        $this->assertEquals(
            $date->format($format),
            $this->timePeriod->getEnd($format)
        );
    }
}
