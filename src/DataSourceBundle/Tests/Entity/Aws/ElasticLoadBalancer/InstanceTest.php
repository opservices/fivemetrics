<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 15/02/17
 * Time: 17:36
 */

namespace DataSourceBundle\Tests\Entity\Aws\ElasticLoadBalancer;

use DataSourceBundle\Entity\Aws\ElasticLoadBalancer\Instance;
use PHPUnit\Framework\TestCase;

/**
 * Class InstanceTest
 * @package DataSourceBundle\Tests\Entity\Aws\ElasticLoadBalancer
 */
class InstanceTest extends TestCase
{
    /**
     * @var Instance
     */
    protected $instance;

    public function setUp()
    {
        $this->instance = new Instance("id");
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals(
            "id",
            $this->instance->getInstanceId()
        );
    }

    /**
     * @test
     */
    public function changeValues()
    {
        $this->instance->setInstanceId("b");

        $this->assertEquals(
            "b",
            $this->instance->getInstanceId()
        );
    }
}
