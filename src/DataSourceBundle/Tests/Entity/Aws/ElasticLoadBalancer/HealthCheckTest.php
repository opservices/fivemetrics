<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 15/02/17
 * Time: 17:23
 */

namespace DataSourceBundle\Tests\Entity\Aws\ElasticLoadBalancer;

use DataSourceBundle\Entity\Aws\ElasticLoadBalancer\HealthCheck;
use PHPUnit\Framework\TestCase;

/**
 * Class HealthCheckTest
 * @package DataSourceBundle\Tests\Entity\Aws\ElasticLoadBalancer
 */
class HealthCheckTest extends TestCase
{
    /**
     * @var HealthCheck
     */
    protected $healthCheck;

    public function setUp()
    {
        $this->healthCheck = new HealthCheck("a", 1, 10, 20, 30);
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals(
            "a",
            $this->healthCheck->getTarget()
        );

        $this->assertEquals(
            1,
            $this->healthCheck->getInterval()
        );

        $this->assertEquals(
            10,
            $this->healthCheck->getTimeout()
        );

        $this->assertEquals(
            20,
            $this->healthCheck->getUnhealthyThreshold()
        );

        $this->assertEquals(
            30,
            $this->healthCheck->getHealthyThreshold()
        );
    }

    /**
     * @test
     */
    public function changeValues()
    {
        $this->healthCheck->setTarget("b")
            ->setInterval(2)
            ->setTimeout(20)
            ->setUnhealthyThreshold(30)
            ->setHealthyThreshold(40);

        $this->assertEquals(
            "b",
            $this->healthCheck->getTarget()
        );

        $this->assertEquals(
            2,
            $this->healthCheck->getInterval()
        );

        $this->assertEquals(
            20,
            $this->healthCheck->getTimeout()
        );

        $this->assertEquals(
            30,
            $this->healthCheck->getUnhealthyThreshold()
        );

        $this->assertEquals(
            40,
            $this->healthCheck->getHealthyThreshold()
        );
    }
}
