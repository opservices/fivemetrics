<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 15/02/17
 * Time: 18:23
 */

namespace DataSourceBundle\Tests\Entity\Aws\ElasticLoadBalancer;

use DataSourceBundle\Collection\Aws\ElasticLoadBalancer\BackendServerDescriptionCollection;
use DataSourceBundle\Collection\Aws\ElasticLoadBalancer\InstanceCollection;
use DataSourceBundle\Collection\Aws\ElasticLoadBalancer\InstanceHealthCollection;
use DataSourceBundle\Collection\Aws\ElasticLoadBalancer\ListenerDescription\ListenerDescriptionCollection;
use DataSourceBundle\Collection\Aws\ElasticLoadBalancer\Policy\AppCookieStickinessPolicyCollection;
use DataSourceBundle\Collection\Aws\ElasticLoadBalancer\Policy\LBCookieStickinessPolicyCollection;
use DataSourceBundle\Entity\Aws\ElasticLoadBalancer\BackendServerDescription;
use DataSourceBundle\Entity\Aws\ElasticLoadBalancer\ElasticLoadBalancer;
use DataSourceBundle\Entity\Aws\ElasticLoadBalancer\HealthCheck;
use DataSourceBundle\Entity\Aws\ElasticLoadBalancer\Instance;
use DataSourceBundle\Entity\Aws\ElasticLoadBalancer\InstanceHealth;
use DataSourceBundle\Entity\Aws\ElasticLoadBalancer\ListenerDescription\Listener;
use DataSourceBundle\Entity\Aws\ElasticLoadBalancer\ListenerDescription\ListenerDescription;
use DataSourceBundle\Entity\Aws\ElasticLoadBalancer\Policies\AppCookieStickinessPolicy;
use DataSourceBundle\Entity\Aws\ElasticLoadBalancer\Policies\LBCookieStickinessPolicy;
use DataSourceBundle\Entity\Aws\ElasticLoadBalancer\Policies\Policies;
use DataSourceBundle\Entity\Aws\ElasticLoadBalancer\SourceSecurityGroup;
use EssentialsBundle\Entity\DateTime\DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Class ElasticLoadBalancerTest
 * @package DataSourceBundle\Tests\Entity\Aws\ElasticLoadBalancer
 */
class ElasticLoadBalancerTest extends TestCase
{
    /**
     * @var ElasticLoadBalancer
     */
    protected $elb;

    public function setUp()
    {
        $this->elb = new ElasticLoadBalancer(
            "name",
            "dns",
            "canonicalId",
            new ListenerDescriptionCollection(),
            new Policies(
                new AppCookieStickinessPolicyCollection(),
                new LBCookieStickinessPolicyCollection(),
                []
            ),
            new BackendServerDescriptionCollection(),
            [],
            [],
            new InstanceCollection(),
            new HealthCheck("target", 60, 30, 10, 20),
            new SourceSecurityGroup("owner", "groupName"),
            [],
            new DateTime(),
            "scheme",
            "vpcId",
            "canonicalName",
            new InstanceHealthCollection()
        );
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals(
            "name",
            $this->elb->getLoadBalancerName()
        );

        $this->assertEquals(
            "dns",
            $this->elb->getDNSName()
        );

        $this->assertEquals(
            "canonicalName",
            $this->elb->getCanonicalHostedZoneName()
        );

        $this->assertEquals(
            "canonicalId",
            $this->elb->getCanonicalHostedZoneNameID()
        );

        $this->assertInstanceOf(
            'DataSourceBundle\Collection\Aws\ElasticLoadBalancer\ListenerDescription\ListenerDescriptionCollection',
            $this->elb->getListenerDescriptions()
        );

        $this->assertInstanceOf(
            'DataSourceBundle\Entity\Aws\ElasticLoadBalancer\Policies\Policies',
            $this->elb->getPolicies()
        );

        $this->assertInstanceOf(
            'DataSourceBundle\Collection\Aws\ElasticLoadBalancer\BackendServerDescriptionCollection',
            $this->elb->getBackendServerDescriptions()
        );

        $this->assertEmpty($this->elb->getAvailabilityZones());

        $this->assertEmpty($this->elb->getSubnets());

        $this->assertEquals(
            "vpcId",
            $this->elb->getVPCId()
        );

        $this->assertInstanceOf(
            'DataSourceBundle\Collection\Aws\ElasticLoadBalancer\InstanceCollection',
            $this->elb->getInstances()
        );

        $this->assertInstanceOf(
            'DataSourceBundle\Entity\Aws\ElasticLoadBalancer\HealthCheck',
            $this->elb->getHealthCheck()
        );

        $this->assertInstanceOf(
            'DataSourceBundle\Entity\Aws\ElasticLoadBalancer\SourceSecurityGroup',
            $this->elb->getSourceSecurityGroup()
        );

        $this->assertEmpty($this->elb->getSecurityGroups());

        $this->assertInstanceOf(
            'EssentialsBundle\Entity\DateTime\DateTime',
            $this->elb->getCreatedTime()
        );

        $this->assertEquals(
            "scheme",
            $this->elb->getScheme()
        );

        $this->assertInstanceOf(
            'DataSourceBundle\Collection\Aws\ElasticLoadBalancer\InstanceHealthCollection',
            $this->elb->getInstanceHealth()
        );
    }

    /**
     * @test
     */
    public function setLoadBalancerName()
    {
        $this->elb->setLoadBalancerName("test.unit");

        $this->assertEquals(
            "test.unit",
            $this->elb->getLoadBalancerName()
        );
    }

    /**
     * @test
     */
    public function setDNSName()
    {
        $this->elb->setDNSName("dns.name");

        $this->assertEquals(
            "dns.name",
            $this->elb->getDNSName()
        );
    }

    /**
     * @test
     */
    public function setCanonicalHostedZoneName()
    {
        $this->elb->setCanonicalHostedZoneName("canonicalName.test");

        $this->assertEquals(
            "canonicalName.test",
            $this->elb->getCanonicalHostedZoneName()
        );
    }

    /**
     * @test
     */
    public function setCanonicalHostedZoneNameID()
    {
        $this->elb->setCanonicalHostedZoneNameID("canonicalId.test");

        $this->assertEquals(
            "canonicalId.test",
            $this->elb->getCanonicalHostedZoneNameID()
        );
    }

    /**
     * @test
     */
    public function setListenerDescriptions()
    {
        $listenerDescriptions = new ListenerDescriptionCollection();
        $listenerDescriptions->add(
            new ListenerDescription(
                new Listener(10, "test", 20, "test"),
                [ "test" ]
            )
        );

        $this->elb->setListenerDescriptions($listenerDescriptions);

        $this->assertEquals(
            $listenerDescriptions,
            $this->elb->getListenerDescriptions()
        );
    }

    /**
     * @test
     */
    public function setPolicies()
    {
        $appCookieStickinessPolicies = new AppCookieStickinessPolicyCollection();
        $appCookieStickinessPolicies->add(
            new AppCookieStickinessPolicy("name", "policy")
        );

        $LBCookieStickinessPolicies = new LBCookieStickinessPolicyCollection();
        $LBCookieStickinessPolicies->add(
            new LBCookieStickinessPolicy(10, "policy")
        );

        $policies = new Policies(
            $appCookieStickinessPolicies,
            $LBCookieStickinessPolicies,
            [ "test" ]
        );

        $this->elb->setPolicies($policies);

        $this->assertEquals(
            $policies,
            $this->elb->getPolicies()
        );
    }

    /**
     * @test
     */
    public function setBackendServerDescriptions()
    {
        $serverDescs = new BackendServerDescriptionCollection();
        $serverDescs->add(
            new BackendServerDescription(
                10,
                [ "policy" ]
            )
        );

        $this->elb->setBackendServerDescriptions($serverDescs);

        $this->assertEquals(
            $serverDescs,
            $this->elb->getBackendServerDescriptions()
        );
    }

    /**
     * @test
     */
    public function setAvailabilityZones()
    {
        $this->elb->setAvailabilityZones([ "az1" ]);

        $this->assertEquals(
            [ "az1" ],
            $this->elb->getAvailabilityZones()
        );
    }

    /**
     * @test
     */
    public function setSubnets()
    {
        $this->elb->setSubnets([ "subnet" ]);

        $this->assertEquals(
            [ "subnet" ],
            $this->elb->getSubnets()
        );
    }

    /**
     * @test
     */
    public function setVPCId()
    {
        $this->elb->setVPCId("vpcId.test");

        $this->assertEquals(
            "vpcId.test",
            $this->elb->getVPCId()
        );
    }

    /**
     * @test
     */
    public function setInstances()
    {
        $instances = new InstanceCollection();
        $instances->add(
            new Instance("id.test")
        );

        $this->elb->setInstances($instances);

        $this->assertEquals(
            $instances,
            $this->elb->getInstances()
        );
    }

    /**
     * @test
     */
    public function setHealthCheck()
    {
        $healthCheck = new HealthCheck("target.test", 60, 120, 30, 40);

        $this->elb->setHealthCheck($healthCheck);

        $this->assertEquals(
            $healthCheck,
            $this->elb->getHealthCheck()
        );
    }

    /**
     * @test
     */
    public function setSourceSecurityGroup()
    {
        $sSecurityGroup = new SourceSecurityGroup("owner.test", "test.unit");
        $this->elb->setSourceSecurityGroup($sSecurityGroup);

        $this->assertEquals(
            $sSecurityGroup,
            $this->elb->getSourceSecurityGroup()
        );
    }

    /**
     * @test
     */
    public function setSecurityGroups()
    {
        $this->elb->setSecurityGroups([ "test" ]);

        $this->assertEquals(
            [ "test" ],
            $this->elb->getSecurityGroups()
        );
    }

    /**
     * @test
     */
    public function setCreatedTime()
    {
        $this->elb->setCreatedTime(
            DateTime::createFromFormat('Y-m-d H:i', "2017-02-15 19:21")
        );

        $this->assertEquals(
            DateTime::createFromFormat('Y-m-d H:i', "2017-02-15 19:21"),
            $this->elb->getCreatedTime()
        );
    }

    /**
     * @test
     */
    public function setScheme()
    {
        $this->elb->setScheme("test.unit");

        $this->assertEquals(
            "test.unit",
            $this->elb->getScheme()
        );
    }

    /**
     * @test
     */
    public function setInstanceHealth()
    {
        $iHealths = new InstanceHealthCollection();
        $iHealths->add(
            new InstanceHealth(
                "id.test",
                "state.test",
                "reason.test",
                "des.test"
            )
        );

        $this->elb->setInstanceHealth($iHealths);

        $this->assertEquals(
            $iHealths,
            $this->elb->getInstanceHealth()
        );
    }
}
