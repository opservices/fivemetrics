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
 * Class AppCookieStickinessPolicyCollection
 * @package DataSourceBundle\InstanceCollection\Aws\ElasticLoadBalancer\Policy
 */
class AppCookieStickinessPolicyCollection extends TypedCollectionAbstract
{
    public function getClass(): string
    {
        return 'DataSourceBundle\Entity\Aws\ElasticLoadBalancer\Policies\AppCookieStickinessPolicy';
    }

    /**
     * {@inheritDoc}
     */
    protected function onChanged($added = null, $removed = null)
    {
    }
}
