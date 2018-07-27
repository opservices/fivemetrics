<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 17/01/17
 * Time: 16:01
 */

namespace DataSourceBundle\Aws\EC2\Measurement\EC2;

use DataSourceBundle\Collection\Aws\EC2\Subnet\SubnetCollection;
use DataSourceBundle\Aws\MeasurementAbstract;
use DataSourceBundle\Entity\Aws\Region\RegionInterface;
use EssentialsBundle\Collection\Metric\MetricCollection;
use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\Entity\Metric\Builder;

/**
 * Class Subnet
 * @package DataSourceBundle\Aws\EC2\Measurement\EC2
 */
class Subnets extends MeasurementAbstract
{
    /**
     * @var SubnetCollection
     */
    protected $subnets;

    /**
     * Subnet constructor.
     * @param RegionInterface $region
     * @param DateTime $dateTime
     * @param SubnetCollection $subnets
     */
    public function __construct(
        RegionInterface $region,
        DateTime $dateTime,
        SubnetCollection $subnets
    ) {
        parent::__construct($region, $dateTime);
        $this->setSubnets($subnets);
    }

    /**
     * @return SubnetCollection
     */
    public function getSubnets(): SubnetCollection
    {
        return $this->subnets;
    }

    /**
     * @param SubnetCollection $subnets
     * @return Subnets
     */
    public function setSubnets(SubnetCollection $subnets): Subnets
    {
        $this->subnets = $subnets;
        return $this;
    }

    /**
     * @return MetricCollection
     */
    public function getMetrics(): MetricCollection
    {
        $buildData = [];
        $subnets = $this->getSubnets();

        foreach ($subnets as $subnet) {
            $key = $subnet->getVpcId() . $subnet->getSubnetId();

            $buildData[$key] = [
                'name' => $this->getName([ 'ec2', 'subnetAvailableIps' ]),
                'tags' => $this->getTags($subnet),
                'points' => [
                    [
                        'value' => $subnet->getAvailableIpAddressCount(),
                        'time' => $this->getMetricsDatetime()
                    ]
                ]
            ];
        }

        return Builder::build(array_values($buildData));
    }

    /**
     * @param \DataSourceBundle\Entity\Aws\EC2\Subnet\Subnet $subnet
     * @return array
     */
    protected function getTags(\DataSourceBundle\Entity\Aws\EC2\Subnet\Subnet $subnet): array
    {
        $tags = [
            [
                'key' => '::fm::region',
                'value' => $this->getRegion()->getCode()
            ],
            [
                'key' => '::fm::availabilityZone',
                'value' => $subnet->getAvailabilityZone()
            ],
            [
                'key' => '::fm::subnet',
                'value' => $subnet->getCidrBlock()
            ]
        ];

        $awsTags = array_map(function ($tag) {
            return [
                'key'   => $tag['Key'],
                'value' => $tag['Value'],
            ];
        }, $subnet->getTags()->toArray());

        return array_merge($tags, $awsTags);
    }
}
