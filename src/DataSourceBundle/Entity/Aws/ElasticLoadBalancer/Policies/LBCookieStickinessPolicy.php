<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 04/02/17
 * Time: 15:43
 */

namespace DataSourceBundle\Entity\Aws\ElasticLoadBalancer\Policies;

use DataSourceBundle\Entity\Aws\EntityAbstract;

/**
 * Class LBCookieStickinessPolicy
 * @package DataSourceBundle\Entity\Aws\ElasticLoadBalancer\Policies
 */
class LBCookieStickinessPolicy extends EntityAbstract
{
    /**
     * @var int
     */
    protected $cookieExpirationPeriod;

    /**
     * @var string
     */
    protected $policyName;

    /**
     * LBCookieStickinessPolicy constructor.
     * @param int $cookieExpirationPeriod
     * @param string $policyName
     */
    public function __construct(
        int $cookieExpirationPeriod,
        string $policyName
    ) {
        $this->setCookieExpirationPeriod($cookieExpirationPeriod)
            ->setPolicyName($policyName);
    }

    /**
     * @return int
     */
    public function getCookieExpirationPeriod(): int
    {
        return $this->cookieExpirationPeriod;
    }

    /**
     * @param int $cookieExpirationPeriod
     * @return LBCookieStickinessPolicy
     */
    public function setCookieExpirationPeriod(int $cookieExpirationPeriod): LBCookieStickinessPolicy
    {
        $this->cookieExpirationPeriod = $cookieExpirationPeriod;
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
     * @return LBCookieStickinessPolicy
     */
    public function setPolicyName(string $policyName): LBCookieStickinessPolicy
    {
        $this->policyName = $policyName;
        return $this;
    }
}
