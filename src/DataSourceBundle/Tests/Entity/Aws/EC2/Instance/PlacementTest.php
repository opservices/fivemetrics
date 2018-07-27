<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 17/02/17
 * Time: 14:16
 */

namespace DataSourceBundle\Tests\Entity\Aws\EC2\Instance;

use DataSourceBundle\Entity\Aws\EC2\Instance\Placement;
use PHPUnit\Framework\TestCase;

/**
 * Class PlacementTest
 * @package DataSourceBundle\Tests\Entity\Aws\EC2\Instance
 */
class PlacementTest extends TestCase
{
    /**
     * @var Placement
     */
    protected $placement;

    public function setUp()
    {
        $this->placement = new Placement(
            'availabilityZone',
            'default',
            'groupName',
            'hostId',
            'affinity'
        );
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals(
            'availabilityZone',
            $this->placement->getAvailabilityZone()
        );

        $this->assertEquals(
            'default',
            $this->placement->getTenancy()
        );

        $this->assertEquals(
            'groupName',
            $this->placement->getGroupName()
        );

        $this->assertEquals(
            'hostId',
            $this->placement->getHostId()
        );

        $this->assertEquals(
            'affinity',
            $this->placement->getAffinity()
        );
    }

    /**
     * @test
     */
    public function setAvailabilityZone()
    {
        $this->placement->setAvailabilityZone('availabilityZone.test');

        $this->assertEquals(
            'availabilityZone.test',
            $this->placement->getAvailabilityZone()
        );
    }

    /**
     * @test
     */
    public function setTenancy()
    {
        $this->placement->setTenancy('dedicated');

        $this->assertEquals(
            'dedicated',
            $this->placement->getTenancy()
        );
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function setInvalidTenancy()
    {
        $this->placement->setTenancy('test');
    }

    /**
     * @test
     */
    public function setGroupName()
    {
        $this->placement->setGroupName('groupName.test');

        $this->assertEquals(
            'groupName.test',
            $this->placement->getGroupName()
        );
    }
}
