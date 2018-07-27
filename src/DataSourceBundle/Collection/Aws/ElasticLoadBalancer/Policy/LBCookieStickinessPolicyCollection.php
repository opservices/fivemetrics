<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 04/02/17
 * Time: 15:41
 */

namespace DataSourceBundle\Collection\Aws\ElasticLoadBalancer\Policy;

use EssentialsBundle\Collection\TypedCollectionAbstract;

/**
 * Class LBCookieStickinessPolicyCollection
 * @package DataSourceBundle\InstanceCollection\Aws\ElasticLoadBalancer\Policy
 */
class LBCookieStickinessPolicyCollection extends TypedCollectionAbstract
{
    public function getClass(): string
    {
        return 'DataSourceBundle\Entity\Aws\ElasticLoadBalancer\Policies\LBCookieStickinessPolicy';
    }

    /**
     * {@inheritDoc}
     */
    protected function onChanged($added = null, $removed = null)
    {
    }
}
