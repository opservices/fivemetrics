<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 22/03/17
 * Time: 10:43
 */

namespace DataSourceBundle\Tests\Gearman\Queue\Collector\Generic\Processor\Aws\EC2\Measurement;

use DataSourceBundle\Collection\Aws\Tag\TagCollection;
use DataSourceBundle\Collection\Aws\EC2\BlockDeviceMappingCollection;
use DataSourceBundle\Collection\Aws\EC2\Instance\InstanceCollection;
use DataSourceBundle\Collection\Aws\EC2\Instance\InstanceIndexer;
use DataSourceBundle\Collection\Aws\EC2\NetworkInterfaceCollection;
use DataSourceBundle\Collection\Aws\EC2\ProductCodeCollection;
use DataSourceBundle\Collection\Aws\EC2\Reservation\Instance\RecurringChargesCollection;
use DataSourceBundle\Collection\Aws\EC2\Reservation\Instance\ReservationCollection;
use DataSourceBundle\Collection\Aws\EC2\SecurityGroupCollection;
use DataSourceBundle\Collection\Aws\EC2\Subnet\Ipv6\Ipv6CidrBlockAssociationCollection;
use DataSourceBundle\Collection\Aws\EC2\Subnet\SubnetCollection;
use EssentialsBundle\Collection\TypedCollectionAbstract;
use DataSourceBundle\Entity\Aws\Region\RegionInterface;
use DataSourceBundle\Entity\Aws\Region\RegionProvider;
use DataSourceBundle\Entity\Aws\Tag\Tag;
use DataSourceBundle\Entity\Aws\EC2\Instance\IamInstanceProfile;
use DataSourceBundle\Entity\Aws\EC2\Instance\Instance;
use DataSourceBundle\Entity\Aws\EC2\Instance\InstanceState;
use DataSourceBundle\Entity\Aws\EC2\Instance\Monitoring;
use DataSourceBundle\Entity\Aws\EC2\Instance\Placement;
use DataSourceBundle\Entity\Aws\EC2\Instance\ProductCode;
use DataSourceBundle\Entity\Aws\EC2\Instance\StateReason;
use DataSourceBundle\Entity\Aws\EC2\Reservation\Instance\RecurringCharges;
use DataSourceBundle\Entity\Aws\EC2\Reservation\Instance\Reservation;
use DataSourceBundle\Entity\Aws\EC2\Subnet\Subnet;
use EssentialsBundle\Entity\DateTime\DateTime;
use DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\Filter;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EC2\Measurement\Builder;
use PHPUnit\Framework\TestCase;

/**
 * Class BuilderTest
 * @package DataSourceBundle\Tests\Gearman\Queue\Collector\Generic\Processor\Aws\EC2\Measurement
 */
class BuilderTest extends TestCase
{
    /**
     * @test
     * @dataProvider buildDataProvider
     */
    public function buildMeasurements(
        RegionInterface $region,
        DateTime $datetime,
        TypedCollectionAbstract $collection,
        Filter $filter,
        int $expectedMetricsCount,
        ReservationCollection $reserves = null
    ) {

        $measurements = Builder::buildMeasurements(
            $region,
            $datetime,
            $collection,
            $filter,
            $reserves
        );

        $metrics = $measurements->getMetrics();
        $this->assertEquals(
            $expectedMetricsCount,
            count($metrics)
        );


        foreach ($metrics as $metric) {
            $this->assertEquals(
                1,
                $metric->getPoints()->at(0)->getValue()
            );
        }
    }

    public function buildDataProvider()
    {
        return [
            [
                RegionProvider::factory('us-east-1'),
                DateTime::createFromFormat(
                    'Y-m-d H:i:s',
                    '2017-08-07 14:10:00'
                ),
                $this->getEC2InstancesTest(),
                new Filter('EC2', [ 'Instances' ]),
                1
            ],
            [
                RegionProvider::factory('us-east-1'),
                DateTime::createFromFormat(
                    'Y-m-d H:i:s',
                    '2017-08-07 14:10:00'
                ),
                $this->getEC2InstancesTest(),
                new Filter('EC2', [ 'Reserves' ]),
                2,
                $this->getReservationsTest()
            ]
        ];
    }

    public function getSubnetsTest(): SubnetCollection
    {
        return new SubnetCollection([ new Subnet(
            'vpcId',
            'subnetId',
            'pending',
            'availabilityZone',
            1,
            'cidrBlock',
            false,
            false,
            false,
            new TagCollection(),
            new Ipv6CidrBlockAssociationCollection()
        )]);
    }

    public function getEC2InstancesTest(): InstanceCollection
    {
        return new InstanceCollection(
            [
            new Instance(
                'i-0db441fbe040a2efc',
                'ami-6d1c2007',
                'PrivateDnsName',
                'PublicDnsName',
                'StateTransitionReason',
                0,
                'c4.large',
                'x86_64',
                'ebs',
                '/dev/sda1',
                'hvm',
                '148217644108981449',
                'xen',
                false,
                DateTime::createFromFormat('Y-m-d H:i', '2017-03-21 09:52'),
                new Monitoring('disabled'),
                new Placement('us-east-1a'),
                new InstanceState(16, 'running'),
                new TagCollection([new Tag('Name', 'test')]),
                new ProductCodeCollection([
                    new ProductCode('aw0evgkw8e5c1q413zgy5pjce', 'marketplace')
                ]),
                new BlockDeviceMappingCollection(),
                new SecurityGroupCollection(),
                new NetworkInterfaceCollection(),
                'sriovNetSupport',
                'spotInstanceRequestId',
                'ramdiskId',
                'publicIpAddress',
                'Linux/Unix',
                'kernelId',
                'instanceLifecycle',
                false,
                'keyName',
                'subnetId',
                'vpcId',
                true,
                'privateIpAddress',
                new IamInstanceProfile('arn', 'id'),
                new StateReason('code', 'message')
            )
            ],
            new InstanceIndexer()
        );
    }

    public function getReservationsTest(): ReservationCollection
    {
        return new ReservationCollection([new Reservation(
            'test',
            'c4.large',
            DateTime::createFromFormat('Y-m-d H:i', '2017-03-17 09:05'),
            DateTime::createFromFormat('Y-m-d H:i', '2018-03-17 09:05'),
            31536000,
            0.01,
            0,
            2,
            'Linux/UNIX',
            'active',
            'default',
            'USD',
            'Partial Upfront',
            new RecurringChargesCollection([ new RecurringCharges(0.01, 'Hourly') ]),
            'standard',
            'Region',
            null,
            new TagCollection([ new Tag('unit', 'test') ])
        ), new Reservation(
            'test',
            'c4.large',
            DateTime::createFromFormat('Y-m-d H:i', '2017-03-17 09:05'),
            DateTime::createFromFormat('Y-m-d H:i', '2018-03-17 09:05'),
            31536000,
            0.01,
            0,
            2,
            'Linux/UNIX',
            'retired',
            'default',
            'USD',
            'Partial Upfront',
            new RecurringChargesCollection([ new RecurringCharges(0.01, 'Hourly') ]),
            'standard',
            'Region',
            '',
            new TagCollection([ new Tag('unit', 'test') ])
        )]);
    }
}
