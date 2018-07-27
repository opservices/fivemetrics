<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 15/02/17
 * Time: 17:14
 */

namespace DataSourceBundle\Tests\Entity\Aws\ElasticLoadBalancer;

use DataSourceBundle\Entity\Aws\ElasticLoadBalancer\BackendServerDescription;
use PHPUnit\Framework\TestCase;

/**
 * Class BackendServerDescriptionTest
 * @package DataSourceBundle\Tests\Entity\Aws\ElasticLoadBalancer
 */
class BackendServerDescriptionTest extends TestCase
{
    /**
     * @var BackendServerDescription
     */
    protected $bServerDesc;

    public function setUp()
    {
        $this->bServerDesc = new BackendServerDescription(10, []);
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEmpty(
            $this->bServerDesc->getPolicyNames()
        );

        $this->assertEquals(
            10,
            $this->bServerDesc->getInstancePort()
        );
    }

    /**
     * @test
     */
    public function changeValues()
    {
        $this->bServerDesc->setInstancePort(20);
        $this->bServerDesc->setPolicyNames([ "unit-test" ]);

        $this->assertEquals(
            20,
            $this->bServerDesc->getInstancePort()
        );

        $this->assertEquals(
            [ "unit-test" ],
            $this->bServerDesc->getPolicyNames()
        );
    }
}
