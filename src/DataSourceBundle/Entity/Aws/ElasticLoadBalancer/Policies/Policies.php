<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 04/02/17
 * Time: 15:46
 */

namespace DataSourceBundle\Entity\Aws\ElasticLoadBalancer\Policies;

use DataSourceBundle\Collection\Aws\ElasticLoadBalancer\Policy\AppCookieStickinessPolicyCollection;
use DataSourceBundle\Collection\Aws\ElasticLoadBalancer\Policy\LBCookieStickinessPolicyCollection;
use DataSourceBundle\Entity\Aws\EntityAbstract;

/**
 * Class Policies
 * @package Entity\Aws\ElasticLoadBalancer\Policies
 */
class Policies extends EntityAbstract
{
    /**
     * @var AppCookieStickinessPolicyCollection
     */
    protected $appCookieStickinessPolicies;

    /**
     * @var LBCookieStickinessPolicyCollection
     */
    protected $LBCookieStickinessPolicies;

    /**
     * @var array
     */
    protected $otherPolicies;

    /**
     * Policy constructor.
     * @param AppCookieStickinessPolicyCollection $appCookieStickinessPolicies
     * @param LBCookieStickinessPolicyCollection $LBCookieStickinessPolicies
     * @param array $otherPolicies
     */
    public function __construct(
        AppCookieStickinessPolicyCollection $appCookieStickinessPolicies,
        LBCookieStickinessPolicyCollection $LBCookieStickinessPolicies,
        array $otherPolicies
    ) {
        $this->setAppCookieStickinessPolicies($appCookieStickinessPolicies)
            ->setLBCookieStickinessPolicies($LBCookieStickinessPolicies)
            ->setOtherPolicies($otherPolicies);
    }

    /**
     * @return AppCookieStickinessPolicyCollection
     */
    public function getAppCookieStickinessPolicies(): AppCookieStickinessPolicyCollection
    {
        return $this->appCookieStickinessPolicies;
    }

    /**
     * @param AppCookieStickinessPolicyCollection $appCookieStickinessPolicies
     * @return Policies
     */
    public function setAppCookieStickinessPolicies(
        AppCookieStickinessPolicyCollection $appCookieStickinessPolicies
    ): Policies {
        $this->appCookieStickinessPolicies = $appCookieStickinessPolicies;
        return $this;
    }

    /**
     * @return LBCookieStickinessPolicyCollection
     */
    public function getLBCookieStickinessPolicies(): LBCookieStickinessPolicyCollection
    {
        return $this->LBCookieStickinessPolicies;
    }

    /**
     * @param LBCookieStickinessPolicyCollection $LBCookieStickinessPolicies
     * @return Policies
     */
    public function setLBCookieStickinessPolicies(
        LBCookieStickinessPolicyCollection $LBCookieStickinessPolicies
    ): Policies {
        $this->LBCookieStickinessPolicies = $LBCookieStickinessPolicies;
        return $this;
    }

    /**
     * @return array
     */
    public function getOtherPolicies(): array
    {
        return $this->otherPolicies;
    }

    /**
     * @param array $otherPolicies
     * @return Policies
     */
    public function setOtherPolicies(array $otherPolicies): Policies
    {
        $this->otherPolicies = $otherPolicies;
        return $this;
    }
}
