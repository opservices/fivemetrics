<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 17/03/17
 * Time: 09:00
 */

namespace DataSourceBundle\Tests\Entity\Aws\EC2\Reservation\Instance;

use DataSourceBundle\Collection\Aws\Tag\TagCollection;
use DataSourceBundle\Collection\Aws\EC2\Reservation\Instance\RecurringChargesCollection;
use DataSourceBundle\Entity\Aws\Tag\Tag;
use DataSourceBundle\Entity\Aws\EC2\Reservation\Instance\RecurringCharges;
use DataSourceBundle\Entity\Aws\EC2\Reservation\Instance\Reservation;
use EssentialsBundle\Entity\DateTime\DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Class ReservationTest
 * @package Entity\Aws\EC2\Reservation\Instance
 */
class ReservationTest extends TestCase
{
    /**
     * @var Reservation
     */
    protected $reserve;

    public function setUp()
    {
        $this->reserve = new Reservation(
            'test',
            'c4.large',
            DateTime::createFromFormat('Y-m-d H:i', '2017-03-17 09:05'),
            DateTime::createFromFormat('Y-m-d H:i', '2018-03-17 09:05'),
            31536000,
            0.01,
            0,
            1,
            'Linux/UNIX',
            'active',
            'default',
            'USD',
            'Partial Upfront',
            new RecurringChargesCollection([ new RecurringCharges(0.01, 'Hourly') ]),
            'standard',
            'Region',
            "",
            new TagCollection([ new Tag('unit', 'test') ])
        );
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals('test', $this->reserve->getReservedInstancesId());
        $this->assertEquals('c4.large', $this->reserve->getInstanceType());
        $this->assertEquals(
            '2017-03-17 09:05',
            $this->reserve->getStart()->format('Y-m-d H:i')
        );
        $this->assertEquals(
            '2018-03-17 09:05',
            $this->reserve->getEnd()->format('Y-m-d H:i')
        );
        $this->assertEquals(31536000, $this->reserve->getDuration());
        $this->assertEquals(0.01, $this->reserve->getUsagePrice());
        $this->assertEquals(0, $this->reserve->getFixedPrice());
        $this->assertEquals(1, $this->reserve->getInstanceCount());
        $this->assertEquals(
            'Linux/UNIX',
            $this->reserve->getProductDescription()
        );
        $this->assertEquals('active', $this->reserve->getState());
        $this->assertEquals('default', $this->reserve->getInstanceTenancy());
        $this->assertEquals('USD', $this->reserve->getCurrencyCode());
        $this->assertEquals('Partial Upfront', $this->reserve->getOfferingType());
        $this->assertEquals(
            new RecurringChargesCollection([ new RecurringCharges(0.01, 'Hourly') ]),
            $this->reserve->getRecurringCharges()
        );
        $this->assertEquals('standard', $this->reserve->getOfferingClass());
        $this->assertEquals('Region', $this->reserve->getScope());
        $this->assertEquals('', $this->reserve->getAvailabilityZone());
        $this->assertEquals(
            new TagCollection([ new Tag('unit', 'test') ]),
            $this->reserve->getTags()
        );
    }
}
