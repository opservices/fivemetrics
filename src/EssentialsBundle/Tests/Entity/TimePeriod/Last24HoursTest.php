<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 5/23/17
 * Time: 4:54 PM
 */

namespace EssentialsBundle\Tests\Entity\TimePeriod;

use EssentialsBundle\Entity\TimePeriod\Last24Hours;
use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\Entity\TimePeriod\TimePeriodInterface;

class Last24HoursTest extends TimePeriodTest
{
    protected function setUp()
    {
        $this->timePeriod = new Last24Hours();
    }

    /**
     * @testdox Verify whether current tests are being executed on Last24Hours class.
     * @test
     */
    public function isAnInstanceOfTimePeriodLast24Hours()
    {
        $this->assertInstanceOf(Last24Hours::class, $this->timePeriod);
    }

    /**
     * @testdox Compare whether current date(time) is equals to returned by Last24Hours class on relational format.
     * @test
     */
    public function getTimeRelationalFormat()
    {
        $format = TimePeriodInterface::RELATIONAL_FORMAT;
        $date = new DateTime();
        do {
            $date->modify("now");
            $timePeriod = new Last24Hours();
        } while ($date->format("U") != $timePeriod->getEnd("U"));

        $this->assertEquals(
            $timePeriod->getEnd($format),
            $date->format($format)
        );

        $date->modify("-23 hour");
        $this->assertEquals(
            $timePeriod->getStart($format),
            $date->format($format)
        );
    }

    /**
     * @testdox Compare whether current date(time) is equals to returned by Last24Hours class on no sql format.
     * @test
     */
    public function getTimeNoSqlFormat()
    {
        $format = TimePeriodInterface::NO_SQL_FORMAT;
        $date = new DateTime();
        do {
            $date->modify("now");
            $timePeriod = new Last24Hours();
        } while ($date->format("U") != $timePeriod->getEnd("U"));

        $this->assertEquals(
            $timePeriod->getEnd($format),
            $date->format($format)
        );

        $date->modify("-23 hour");
        $this->assertEquals(
            $timePeriod->getStart($format),
            $date->format($format)
        );
    }

    /**
     * @testdox Verify whether possible update Last24Hours' date(time).
     * @test
     */
    public function update()
    {
        // Wait to have diference between class timeperiod and local timeperiod ($date variable).
        sleep(1);

        // It's used to ensure that both calls are executed on the same second.
        $date = new DateTime();
        do {
            $execTime = time();
            $this->timePeriod->update();
            $date->modify('now');
        } while ($execTime != time());

        $this->assertEquals(
            $this->timePeriod->getEnd(),
            $date->format(TimePeriodInterface::NO_SQL_FORMAT)
        );
    }
}
