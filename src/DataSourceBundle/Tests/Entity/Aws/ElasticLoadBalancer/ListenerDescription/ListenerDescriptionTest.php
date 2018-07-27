<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 15/02/17
 * Time: 10:31
 */

namespace DataSourceBundle\Tests\Entity\Aws\ElasticLoadBalancer\ListenerDescription;

use DataSourceBundle\Entity\Aws\ElasticLoadBalancer\ListenerDescription\Listener;
use DataSourceBundle\Entity\Aws\ElasticLoadBalancer\ListenerDescription\ListenerDescription;
use PHPUnit\Framework\TestCase;

/**
 * Class ListenerDescriptionTest
 * @package DataSourceBundle\Tests\Entity\Aws\ElasticLoadBalancer\ListenerDescription
 */
class ListenerDescriptionTest extends TestCase
{
    /**
     * @var ListenerDescription
     */
    protected $listenerDesc;

    public function setUp()
    {
        $this->listenerDesc = new ListenerDescription(
            new Listener(80, "http", 443, "https"),
            [ "unit-test" ]
        );
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $listener = $this->listenerDesc->getListener();

        $this->assertInstanceOf(
            'DataSourceBundle\Entity\Aws\ElasticLoadBalancer\ListenerDescription\Listener',
            $listener
        );

        $this->assertEquals(
            [ "unit-test" ],
            $this->listenerDesc->getPolicyNames()
        );

        $this->assertEquals(80, $listener->getInstancePort());
        $this->assertEquals("http", $listener->getInstanceProtocol());
        $this->assertEquals(443, $listener->getLoadBalancerPort());
        $this->assertEquals("https", $listener->getProtocol());
        $this->assertEmpty($listener->getSSLCertificateId());
    }

    /**
     * @test
     */
    public function changeValues()
    {
        $listener = new Listener(
            1,
            "a",
            2,
            "b",
            "c"
        );

        $policies = [ "policy" ];

        $this->listenerDesc->setListener($listener)
            ->setPolicyNames($policies);

        $this->assertEquals($listener, $this->listenerDesc->getListener());
        $this->assertEquals($policies, $this->listenerDesc->getPolicyNames());
    }
}
