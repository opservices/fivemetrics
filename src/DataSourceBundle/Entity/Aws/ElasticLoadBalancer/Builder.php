<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 04/02/17
 * Time: 22:55
 */

namespace DataSourceBundle\Entity\Aws\ElasticLoadBalancer;

use DataSourceBundle\Collection\Aws\ElasticLoadBalancer\BackendServerDescriptionCollection;
use DataSourceBundle\Collection\Aws\ElasticLoadBalancer\ElasticLoadBalancerCollection;
use DataSourceBundle\Collection\Aws\ElasticLoadBalancer\InstanceCollection;
use DataSourceBundle\Collection\Aws\ElasticLoadBalancer\InstanceHealthCollection;
use DataSourceBundle\Collection\Aws\ElasticLoadBalancer\ListenerDescription\ListenerDescriptionCollection;
use DataSourceBundle\Collection\Aws\ElasticLoadBalancer\Policy\AppCookieStickinessPolicyCollection;
use DataSourceBundle\Collection\Aws\ElasticLoadBalancer\Policy\LBCookieStickinessPolicyCollection;
use DataSourceBundle\Entity\Aws\ElasticLoadBalancer\ListenerDescription\Listener;
use DataSourceBundle\Entity\Aws\ElasticLoadBalancer\ListenerDescription\ListenerDescription;
use DataSourceBundle\Entity\Aws\ElasticLoadBalancer\Policies\AppCookieStickinessPolicy;
use DataSourceBundle\Entity\Aws\ElasticLoadBalancer\Policies\LBCookieStickinessPolicy;
use DataSourceBundle\Entity\Aws\ElasticLoadBalancer\Policies\Policies;
use EssentialsBundle\Entity\DateTime\DateTime;

/**
 * Class Builder
 * @package DataSourceBundle\Entity\Aws\ElasticLoadBalancer
 */
class Builder
{
    /**
     * @param array $data
     * @return ElasticLoadBalancerCollection
     */
    public static function buildElasticLoadBalancer(array $data): ElasticLoadBalancerCollection
    {
        $elbs = new ElasticLoadBalancerCollection();

        foreach ($data as $elb) {
            $elbs->add(
                new ElasticLoadBalancer(
                    $elb['LoadBalancerName'],
                    $elb['DNSName'],
                    $elb['CanonicalHostedZoneNameID'],
                    self::buildListenerDescriptions($elb['ListenerDescriptions']),
                    self::buildPolicies($elb['Policies']),
                    self::buildBackendServerDescriptions($elb['BackendServerDescriptions']),
                    $elb['AvailabilityZones'],
                    $elb['Subnets'],
                    self::buildInstances($elb['Instances']),
                    self::buildHealthCheck($elb['HealthCheck']),
                    self::buildSourceSecurityGroup($elb['SourceSecurityGroup']),
                    $elb['SecurityGroups'],
                    new DateTime($elb['CreatedTime']),
                    $elb['Scheme'],
                    $elb['VPCId'],
                    $elb['CanonicalHostedZoneName'],
                    (empty($elb['InstanceHealth'])) ? null : self::buildInstanceHealth($elb['InstanceHealth'])
                )
            );
        }

        return $elbs;
    }

    /**
     * @param array $data
     * @return InstanceHealthCollection
     */
    public static function buildInstanceHealth(array $data): InstanceHealthCollection
    {
        $instances = new InstanceHealthCollection();

        foreach ($data as $instance) {
            $instances->add(
                new InstanceHealth(
                    $instance['InstanceId'],
                    $instance['State'],
                    $instance['ReasonCode'],
                    $instance['Description']
                )
            );
        }

        return $instances;
    }

    /**
     * @param array $data
     * @return SourceSecurityGroup
     */
    protected static function buildSourceSecurityGroup(array $data): SourceSecurityGroup
    {
        return new SourceSecurityGroup(
            $data['OwnerAlias'],
            $data['GroupName']
        );
    }

    /**
     * @param array $data
     * @return HealthCheck
     */
    protected static function buildHealthCheck(array $data): HealthCheck
    {
        return new HealthCheck(
            $data['Target'],
            $data['Interval'],
            $data['Timeout'],
            $data['UnhealthyThreshold'],
            $data['HealthyThreshold']
        );
    }

    /**
     * @param array $data
     * @return InstanceCollection
     */
    protected static function buildInstances(array $data): InstanceCollection
    {
        $instances = new InstanceCollection();

        foreach ($data as $instance) {
            $instances->add(
                new Instance(
                    $instance['InstanceId']
                )
            );
        }

        return $instances;
    }

    /**
     * @param array $data
     * @return BackendServerDescriptionCollection
     */
    protected static function buildBackendServerDescriptions(
        array $data
    ): BackendServerDescriptionCollection {
        $backendServerDescriptions = new BackendServerDescriptionCollection();

        foreach ($data as $desc) {
            $backendServerDescriptions->add(
                new BackendServerDescription(
                    $desc['InstancePort'],
                    $desc['PolicyNames']
                )
            );
        }

        return $backendServerDescriptions;
    }

    /**
     * @param array $data
     * @return Policies
     */
    protected static function buildPolicies(
        array $data
    ): Policies {
        return new Policies(
            self::buildAppCookieStickinessPolicies(
                (is_array($data['AppCookieStickinessPolicies']))
                    ? $data['AppCookieStickinessPolicies']
                    : []
            ),
            self::buildLBCookieStickinessPolicies(
                (is_array($data['LBCookieStickinessPolicies']))
                    ? $data['LBCookieStickinessPolicies']
                    : []
            ),
            (is_array($data['OtherPolicies']))
                ? $data['OtherPolicies']
                : []
        );
    }

    /**
     * @param array $data
     * @return AppCookieStickinessPolicyCollection
     */
    protected static function buildAppCookieStickinessPolicies(
        array $data
    ): AppCookieStickinessPolicyCollection {
        $appCookieStickinessPolicies = new AppCookieStickinessPolicyCollection();

        foreach ($data as $appCookieStickinessPolicy) {
            $appCookieStickinessPolicies->add(
                new AppCookieStickinessPolicy(
                    $appCookieStickinessPolicy['CookieName'],
                    $appCookieStickinessPolicy['PolicyName']
                )
            );
        }

        return $appCookieStickinessPolicies;
    }

    /**
     * @param array $data
     * @return LBCookieStickinessPolicyCollection
     */
    protected static function buildLBCookieStickinessPolicies(
        array $data
    ): LBCookieStickinessPolicyCollection {
        $lbCookieStickinessPolicies = new LBCookieStickinessPolicyCollection();

        foreach ($data as $lbCookieStickinessPolicy) {
            $lbCookieStickinessPolicies->add(
                new LBCookieStickinessPolicy(
                    $lbCookieStickinessPolicy['CookieExpirationPeriod'],
                    $lbCookieStickinessPolicy['PolicyName']
                )
            );
        }

        return $lbCookieStickinessPolicies;
    }

    /**
     * @param array $data
     * @return ListenerDescriptionCollection
     */
    protected static function buildListenerDescriptions(
        array $data
    ): ListenerDescriptionCollection {
        $listenerDesc = new ListenerDescriptionCollection();

        foreach ($data as $desc) {
            $listenerDesc->add(
                new ListenerDescription(
                    new Listener(
                        $desc['Listener']['InstancePort'],
                        $desc['Listener']['InstanceProtocol'],
                        $desc['Listener']['LoadBalancerPort'],
                        $desc['Listener']['Protocol'],
                        $desc['Listener']['SSLCertificateId']
                    ),
                    $desc['PolicyNames']
                )
            );
        }

        return $listenerDesc;
    }
}
