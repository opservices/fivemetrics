<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 5/23/17
 * Time: 4:35 PM
 */

namespace EssentialsBundle\Tests\Entity\TimePeriod;

use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\Entity\TimePeriod\TimePeriodAbstract;
use EssentialsBundle\Entity\TimePeriod\TimePeriodInterface;
use EssentialsBundle\Entity\TimePeriod\Last15Days;
use EssentialsBundle\Entity\TimePeriod\TimePeriodProvider;
use Symfony\Bundle\FrameworkBundle\Tests\TestCase;

abstract class TimePeriodTest extends TestCase
{
    /**
     * @var TimePeriodInterface
     */
    protected $timePeriod;

    protected function tearDown()
    {
        ini_set("date.timezone", "America/Sao_Paulo");
    }

    /**
     * @testdox Verify whether class Now implements the interface TimePeriodTest.
     * @test
     */
    public function isAnInstanceOfTimePeriod()
    {
        $this->assertInstanceOf(TimePeriodInterface::class, $this->timePeriod);
    }

    /**
     * @testdox Verify whether class Now extends the TimePeriodImp.
     * @test
     */
    public function isAnInstanceOfTimePeriodAbstract()
    {
        $this->assertInstanceOf(TimePeriodAbstract::class, $this->timePeriod);
    }

    /**
     * @test
     */
    public function instantiateClassWithDateTime()
    {
        $timePeriod = new Last15Days(new DateTime());
        $this->assertInstanceOf(TimePeriodAbstract::class, $timePeriod);
    }

    /**
     * @test
     */
    public function getTimeOtherFormat()
    {
        $format = "Y-m H:i:s";
        $date = new DateTime();
        $timePeriodProvider = new TimePeriodProvider();

        do {
            $date->modify("now");
            $timePeriod = $timePeriodProvider->factory();
        } while ($date->format("U") != $timePeriod->getEnd("U"));

        $this->assertEquals($date->format($format), $timePeriod->getEnd($format));
    }
}
