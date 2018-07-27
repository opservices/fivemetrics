<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 15/02/17
 * Time: 10:18
 */

namespace DataSourceBundle\Tests\Entity\Aws\ElasticLoadBalancer\ListenerDescription;

use DataSourceBundle\Entity\Aws\ElasticLoadBalancer\ListenerDescription\Listener;
use PHPUnit\Framework\TestCase;

/**
 * Class ListenerTest
 * @package DataSourceBundle\Tests\Entity\Aws\ElasticLoadBalancer\ListenerDescription
 */
class ListenerTest extends TestCase
{
    /**
     * @var Listener
     */
    protected $listener;

    public function setUp()
    {
        $this->listener = new Listener(
            80,
            "http",
            80,
            "http"
        );
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals(80, $this->listener->getInstancePort());
        $this->assertEquals("http", $this->listener->getInstanceProtocol());
        $this->assertEquals(80, $this->listener->getLoadBalancerPort());
        $this->assertEquals("http", $this->listener->getProtocol());
        $this->assertEmpty($this->listener->getSSLCertificateId());
    }

    /**
     * @test
     */
    public function changeConstructorValues()
    {
        $this->listener->setInstancePort(443)
            ->setInstanceProtocol("https")
            ->setLoadBalancerPort(161)
            ->setProtocol("snmp")
            ->setSSLCertificateId("abc");

        $this->assertEquals(443, $this->listener->getInstancePort());
        $this->assertEquals("https", $this->listener->getInstanceProtocol());
        $this->assertEquals(161, $this->listener->getLoadBalancerPort());
        $this->assertEquals("snmp", $this->listener->getProtocol());
        $this->assertEquals("abc", $this->listener->getSSLCertificateId());
    }
}
