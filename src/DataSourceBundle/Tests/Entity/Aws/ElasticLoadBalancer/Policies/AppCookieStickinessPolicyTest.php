<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 15/02/17
 * Time: 10:43
 */

namespace DataSourceBundle\Tests\Entity\Aws\ElasticLoadBalancer\Policies;

use DataSourceBundle\Entity\Aws\ElasticLoadBalancer\Policies\AppCookieStickinessPolicy;
use PHPUnit\Framework\TestCase;

/**
 * Class AppCookieStickinessPolicyTest
 * @package DataSourceBundle\Tests\Entity\Aws\ElasticLoadBalancer\Policies
 */
class AppCookieStickinessPolicyTest extends TestCase
{
    /**
     * @var AppCookieStickinessPolicy
     */
    protected $appCookieStickinessPolicy;

    public function setUp()
    {
        $this->appCookieStickinessPolicy = new AppCookieStickinessPolicy(
            "unit-test",
            "test"
        );
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals(
            "unit-test",
            $this->appCookieStickinessPolicy->getCookieName()
        );

        $this->assertEquals(
            "test",
            $this->appCookieStickinessPolicy->getPolicyName()
        );
    }

    /**
     * @test
     */
    public function changeValues()
    {
        $this->appCookieStickinessPolicy->setCookieName("a");
        $this->appCookieStickinessPolicy->setPolicyName("b");

        $this->assertEquals(
            "a",
            $this->appCookieStickinessPolicy->getCookieName()
        );

        $this->assertEquals(
            "b",
            $this->appCookieStickinessPolicy->getPolicyName()
        );
    }
}
