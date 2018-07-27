<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 15/02/17
 * Time: 19:50
 */

namespace DataSourceBundle\Tests\Entity\Aws\ElasticLoadBalancer;

use DataSourceBundle\Entity\Aws\ElasticLoadBalancer\Builder;
use PHPUnit\Framework\TestCase;

/**
 * Class BuilderTest
 * @package DataSourceBundle\Tests\Entity\Aws\ElasticLoadBalancer
 */
class BuilderTest extends TestCase
{
    /**
     * @test
     * @dataProvider validELBsData
     * @param $data
     */
    public function buildELB($data)
    {
        $elbs = Builder::buildElasticLoadBalancer([$data]);

        $this->assertInstanceOf(
            "DataSourceBundle\\Collection\\Aws\\ElasticLoadBalancer\\ElasticLoadBalancerCollection",
            $elbs
        );

        $this->assertGreaterThan(0, count($elbs));

        $this->assertEquals(
            json_encode($data),
            json_encode($elbs->current())
        );
    }

    public function validELBsData()
    {
        $elbs = [
            '{
                "LoadBalancerName": "name.test",
                "DNSName": "dns.test",
                "CanonicalHostedZoneName": "canonicalHostedZoneName.test",
                "CanonicalHostedZoneNameID": "canonicalHostedZoneNameID.test",
                "ListenerDescriptions": [
                    {
                        "Listener": {
                            "InstancePort": 80,
                            "InstanceProtocol": "http",
                            "LoadBalancerPort": 80,
                            "Protocol": "http",
                            "SSLCertificateId": "ssl.id"
                        },
                        "PolicyNames": [
                            "PolicyName.test"
                        ]
                    }
                ],
                "Policies": {
                    "AppCookieStickinessPolicies": [
                        {
                            "CookieName": "AppCookieStickinessPolicies.test",
                            "PolicyName": "PolicyName.test"
                        }
                    ],
                    "LBCookieStickinessPolicies": [
                        {
                            "CookieExpirationPeriod": 10,
                            "PolicyName": "PolicyName.test"
                        }
                    ],
                    "OtherPolicies": [
                        "OtherPolicie.test"
                    ]
                },
                "BackendServerDescriptions": [
                    {
                        "InstancePort": 10,
                        "PolicyNames": [
                            "PolicyName.test"
                        ]
                    }
                ],
                "AvailabilityZones": [
                    "az.test"
                ],
                "Subnets": [
                    "Subnet.test"
                ],
                "VPCId": "VPCId.test",
                "Instances": [
                    {
                        "InstanceId": "InstanceId.test"
                    }
                ],
                "HealthCheck": {
                    "Target": "Target.test",
                    "Interval": 10,
                    "Timeout": 5,
                    "UnhealthyThreshold": 2,
                    "HealthyThreshold": 4
                },
                "SourceSecurityGroup": {
                    "OwnerAlias": "OwnerAlias.test",
                    "GroupName": "GroupName.test"
                },
                "SecurityGroups": [
                    "SecurityGroup.test"
                ],
                "CreatedTime": "2017-02-15T20:12:30-02:00",
                "Scheme": "Scheme.test",
                "InstanceHealth": [
                    {
                        "State": "State.test",
                        "ReasonCode": "ReasonCode.test",
                        "Description": "Description.test",
                        "InstanceId": "InstanceId.test"
                    }
                ]
            }',
            '{
                "LoadBalancerName": "name.test",
                "DNSName": "dns.test",
                "CanonicalHostedZoneName": "canonicalHostedZoneName.test",
                "CanonicalHostedZoneNameID": "canonicalHostedZoneNameID.test",
                "ListenerDescriptions": [
                    {
                        "Listener": {
                            "InstancePort": 80,
                            "InstanceProtocol": "http",
                            "LoadBalancerPort": 80,
                            "Protocol": "http",
                            "SSLCertificateId": "ssl.id"
                        },
                        "PolicyNames": [
                            "PolicyName.test"
                        ]
                    }
                ],
                "Policies": {
                    "AppCookieStickinessPolicies": [
                        {
                            "CookieName": "AppCookieStickinessPolicies.test",
                            "PolicyName": "PolicyName.test"
                        }
                    ],
                    "LBCookieStickinessPolicies": [
                        {
                            "CookieExpirationPeriod": 10,
                            "PolicyName": "PolicyName.test"
                        }
                    ],
                    "OtherPolicies": [
                        "OtherPolicie.test"
                    ]
                },
                "BackendServerDescriptions": [
                    {
                        "InstancePort": 10,
                        "PolicyNames": [
                            "PolicyName.test"
                        ]
                    }
                ],
                "AvailabilityZones": [
                    "az.test"
                ],
                "Subnets": [
                    "Subnet.test"
                ],
                "VPCId": "VPCId.test",
                "Instances": [
                    {
                        "InstanceId": "InstanceId.test"
                    }
                ],
                "HealthCheck": {
                    "Target": "Target.test",
                    "Interval": 10,
                    "Timeout": 5,
                    "UnhealthyThreshold": 2,
                    "HealthyThreshold": 4
                },
                "SourceSecurityGroup": {
                    "OwnerAlias": "OwnerAlias.test",
                    "GroupName": "GroupName.test"
                },
                "SecurityGroups": [
                    "SecurityGroup.test"
                ],
                "CreatedTime": "2017-02-15T20:12:30-02:00",
                "Scheme": "Scheme.test"
            }'
        ];

        foreach ($elbs as $elb) {
            yield [ json_decode($elb, true) ];
        }
    }
}
