<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 5/24/17
 * Time: 12:01 PM
 */

namespace EssentialsBundle\Tests\Entity\TimePeriod;

use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\Entity\TimePeriod\Custom;

class CustomTest extends TimePeriodTest
{

    protected function setUp()
    {
        $start = new DateTime();
        $end = new DateTime();
        $this->timePeriod = new Custom($start, $end);
    }

    /**
     * @testdox Verify whether current tests are being executed on TimPeriodCustom class.
     * @test
     */
    public function isAnInstanceOfTimePeriodLastMonth()
    {
        $this->assertInstanceOf(Custom::class, $this->timePeriod);
    }
}
