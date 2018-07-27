<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 17/03/17
 * Time: 09:46
 */

namespace DataSourceBundle\Tests\Entity\Aws\EC2\Reservation\Instance;

use DataSourceBundle\Entity\Aws\EC2\Reservation\Instance\RecurringCharges;
use PHPUnit\Framework\TestCase;

/**
 * Class RecurringChargesTest
 * @package DataSourceBundle\Tests\Entity\Aws\EC2\Reservation\Instance
 */
class RecurringChargesTest extends TestCase
{
    /**
     * @var RecurringCharges
     */
    protected $recurringCharges;

    public function setUp()
    {
        $this->recurringCharges = new RecurringCharges(
            0.1,
            'Hourly'
        );
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals(0.1, $this->recurringCharges->getAmount());
        $this->assertEquals('Hourly', $this->recurringCharges->getFrequency());
    }
}
