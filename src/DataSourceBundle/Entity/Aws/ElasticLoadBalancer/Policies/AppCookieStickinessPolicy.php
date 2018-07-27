<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 04/02/17
 * Time: 15:36
 */

namespace DataSourceBundle\Entity\Aws\ElasticLoadBalancer\Policies;

use DataSourceBundle\Entity\Aws\EntityAbstract;

/**
 * Class AppCookieStickinessPolicy
 * @package DataSourceBundle\Entity\Aws\ElasticLoadBalancer\Policies
 */
class AppCookieStickinessPolicy extends EntityAbstract
{
    /**
     * @var string
     */
    protected $cookieName;

    /**
     * @var string
     */
    protected $policyName;

    /**
     * AppCookieStickinessPolicy constructor.
     * @param string $cookieName
     * @param string $policyName
     */
    public function __construct(
        string $cookieName,
        string $policyName
    ) {
        $this->setCookieName($cookieName)
            ->setPolicyName($policyName);
    }

    /**
     * @return string
     */
    public function getCookieName(): string
    {
        return $this->cookieName;
    }

    /**
     * @param string $cookieName
     * @return AppCookieStickinessPolicy
     */
    public function setCookieName(string $cookieName): AppCookieStickinessPolicy
    {
        $this->cookieName = $cookieName;
        return $this;
    }

    /**
     * @return string
     */
    public function getPolicyName(): string
    {
        return $this->policyName;
    }

    /**
     * @param string $policyName
     * @return AppCookieStickinessPolicy
     */
    public function setPolicyName(string $policyName): AppCookieStickinessPolicy
    {
        $this->policyName = $policyName;
        return $this;
    }
}
