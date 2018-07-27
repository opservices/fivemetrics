<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 15/02/17
 * Time: 10:55
 */

namespace DataSourceBundle\Tests\Entity\Aws\ElasticLoadBalancer\Policies;

use DataSourceBundle\Collection\Aws\ElasticLoadBalancer\Policy\AppCookieStickinessPolicyCollection;
use DataSourceBundle\Collection\Aws\ElasticLoadBalancer\Policy\LBCookieStickinessPolicyCollection;
use DataSourceBundle\Entity\Aws\ElasticLoadBalancer\Policies\AppCookieStickinessPolicy;
use DataSourceBundle\Entity\Aws\ElasticLoadBalancer\Policies\LBCookieStickinessPolicy;
use DataSourceBundle\Entity\Aws\ElasticLoadBalancer\Policies\Policies;
use PHPUnit\Framework\TestCase;

/**
 * Class Policies
 * @package DataSourceBundle\Tests\Entity\Aws\ElasticLoadBalancer\Policies
 */
class PoliciesTest extends TestCase
{
    /**
     * @var Policies
     */
    protected $policies;

    public function setUp()
    {
        $lbPolicies = new LBCookieStickinessPolicyCollection();

        $lbPolicies->add(
            new LBCookieStickinessPolicy(
                10,
                "unit-test"
            )
        );

        $appPolicies = new AppCookieStickinessPolicyCollection();

        $appPolicies->add(
            new AppCookieStickinessPolicy(
                "unit-test",
                "test"
            )
        );

        $this->policies = new Policies($appPolicies, $lbPolicies, []);
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEmpty(
            $this->policies->getOtherPolicies()
        );

        $this->assertInstanceOf(
            'DataSourceBundle\Collection\Aws\ElasticLoadBalancer\Policy\AppCookieStickinessPolicyCollection',
            $this->policies->getAppCookieStickinessPolicies()
        );

        $this->assertInstanceOf(
            'DataSourceBundle\Collection\Aws\ElasticLoadBalancer\Policy\LBCookieStickinessPolicyCollection',
            $this->policies->getLBCookieStickinessPolicies()
        );
    }

    /**
     * @test
     */
    public function changeValues()
    {
        $lbPolicies = new LBCookieStickinessPolicyCollection();

        $lbPolicies->add(
            new LBCookieStickinessPolicy(
                20,
                "test"
            )
        );

        $appPolicies = new AppCookieStickinessPolicyCollection();

        $appPolicies->add(
            new AppCookieStickinessPolicy(
                "a",
                "a"
            )
        );

        $otherPolicies = [ "a", "b" ];

        $this->policies->setAppCookieStickinessPolicies($appPolicies);
        $this->policies->setLBCookieStickinessPolicies($lbPolicies);
        $this->policies->setOtherPolicies($otherPolicies);

        $this->assertEquals(
            $otherPolicies,
            $this->policies->getOtherPolicies()
        );

        $this->assertEquals(
            $appPolicies,
            $this->policies->getAppCookieStickinessPolicies()
        );

        $this->assertEquals(
            $lbPolicies,
            $this->policies->getLBCookieStickinessPolicies()
        );
    }
}
