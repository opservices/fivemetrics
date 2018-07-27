<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 17/02/17
 * Time: 14:36
 */

namespace DataSourceBundle\Tests\Entity\Aws\EC2\Instance;

use DataSourceBundle\Entity\Aws\EC2\Instance\Monitoring;
use PHPUnit\Framework\TestCase;

/**
 * Class MonitoringTest
 * @package DataSourceBundle\Tests\Entity\Aws\EC2\Instance
 */
class MonitoringTest extends TestCase
{
    /**
     * @var Monitoring
     */
    protected $monitoring;

    public function setUp()
    {
        $this->monitoring = new Monitoring(
            'disabled'
        );
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals(
            'disabled',
            $this->monitoring->getState()
        );
    }

    /**
     * @test
     */
    public function setState()
    {
        $this->monitoring->setState('disabling');

        $this->assertEquals(
            'disabling',
            $this->monitoring->getState()
        );
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function setInvalidState()
    {
        $this->monitoring->setState('test');
    }
}
