<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 15/02/17
 * Time: 10:51
 */

namespace DataSourceBundle\Tests\Entity\Aws\ElasticLoadBalancer\Policies;

use DataSourceBundle\Entity\Aws\ElasticLoadBalancer\Policies\LBCookieStickinessPolicy;
use PHPUnit\Framework\TestCase;

/**
 * Class LBCookieStickinessPolicyTest
 * @package DataSourceBundle\Tests\Entity\Aws\ElasticLoadBalancer\Policies
 */
class LBCookieStickinessPolicyTest extends TestCase
{
    /**
     * @var LBCookieStickinessPolicy
     */
    protected $LBCookieStickinessPolicy;

    public function setUp()
    {
        $this->LBCookieStickinessPolicy = new LBCookieStickinessPolicy(
            10,
            "unit-test"
        );
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals(
            10,
            $this->LBCookieStickinessPolicy->getCookieExpirationPeriod()
        );

        $this->assertEquals(
            "unit-test",
            $this->LBCookieStickinessPolicy->getPolicyName()
        );
    }

    /**
     * @test
     */
    public function changeValues()
    {
        $this->LBCookieStickinessPolicy->setCookieExpirationPeriod(1);
        $this->LBCookieStickinessPolicy->setPolicyName("policy");

        $this->assertEquals(
            1,
            $this->LBCookieStickinessPolicy->getCookieExpirationPeriod()
        );

        $this->assertEquals(
            "policy",
            $this->LBCookieStickinessPolicy->getPolicyName()
        );
    }
}
