<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 18/12/16
 * Time: 19:01
 */

namespace DataSourceBundle\Aws\EC2;

use Aws\AutoScaling\AutoScalingClient;
use Aws\Ec2\Ec2Client;
use Aws\ElasticLoadBalancing\ElasticLoadBalancingClient;
use DataSourceBundle\Aws\ClientAbstract;
use DataSourceBundle\Collection\Aws\AutoScaling\ActivityCollection;
use DataSourceBundle\Collection\Aws\AutoScaling\AutoScalingGroupCollection;
use DataSourceBundle\Collection\Aws\AutoScaling\InstanceCollection as AutoScalingInstanceCollection;
use DataSourceBundle\Collection\Aws\EBS\Volume\VolumeCollection;
use DataSourceBundle\Collection\Aws\EC2\Instance\InstanceCollection as EC2InstanceCollection;
use DataSourceBundle\Collection\Aws\EC2\Reservation\Instance\ReservationCollection;
use DataSourceBundle\Collection\Aws\EC2\Subnet\SubnetCollection;
use DataSourceBundle\Collection\Aws\ElasticLoadBalancer\ElasticLoadBalancerCollection;
use DataSourceBundle\Collection\Aws\ElasticLoadBalancer\InstanceHealthCollection;
use DataSourceBundle\Entity\Aws\AutoScaling\Builder as AutoScalingBuilder;
use DataSourceBundle\Entity\Aws\EBS\Volume\Builder as VolumeBuilder;
use DataSourceBundle\Entity\Aws\EC2\Instance\Builder as InstanceBuilder;
use DataSourceBundle\Entity\Aws\EC2\Reservation\Instance\Builder as ReservationBuilder;
use DataSourceBundle\Entity\Aws\EC2\Subnet\Builder as SubnetBuilder;
use DataSourceBundle\Entity\Aws\ElasticLoadBalancer\Builder as ELBBuilder;
use DataSourceBundle\Entity\Aws\Region\RegionInterface;

/**
 * Class EC2
 * @package DataSource\Aws\EC2
 */
class EC2 extends ClientAbstract
{
    const EC2_CLIENT_VERSION = '2016-09-15';

    const AUTO_SCALING_CLIENT_VERSION = '2011-01-01';

    const ELASTIC_LOAD_BALANCING_CLIENT_VERSION = '2012-06-01';

    /**
     * @var Ec2Client
     */
    protected $ec2Cli;

    /**
     * @var ElasticLoadBalancingClient
     */
    protected $elbCli;

    /**
     * @var AutoScalingClient
     */
    protected $atScalingCli;

    /**
     * EC2 constructor.
     * @param string $key
     * @param string $secret
     * @param RegionInterface $region
     */
    public function __construct(string $key, string $secret, RegionInterface $region)
    {
        parent::__construct($key, $secret, $region);

        $this->ec2Cli = new Ec2Client([
            "region"      => $this->getRegion()->getCode(),
            "version"     => self::EC2_CLIENT_VERSION,
            "credentials" => $this->getCredential(),
            'http'        => [ 'timeout' => 15 ]
        ]);

        $this->atScalingCli = new AutoScalingClient([
            "region"      => $this->getRegion()->getCode(),
            "version"     => self::AUTO_SCALING_CLIENT_VERSION,
            "credentials" => $this->getCredential(),
            'http'        => [ 'timeout' => 15 ]
        ]);

        $this->elbCli = new ElasticLoadBalancingClient([
            "region"      => $this->getRegion()->getCode(),
            "version"     => self::ELASTIC_LOAD_BALANCING_CLIENT_VERSION,
            "credentials" => $this->getCredential(),
            'http'        => [ 'timeout' => 15 ]
        ]);
    }

    public function __destruct()
    {
        $this->ec2Cli = null;
        $this->elbCli = null;
        $this->atScalingCli = null;
    }

    /**
     * @return AutoScalingInstanceCollection
     */
    public function retrieveAutoScalingInstances(): AutoScalingInstanceCollection
    {
        $instances = $this->atScalingCli->describeAutoScalingInstances()
            ->search("* | [0]");

        return AutoScalingBuilder::buildInstanceCollection($instances);
    }

    /**
     * @param string $elbName
     * @return InstanceHealthCollection
     */
    public function retrieveElasticLoadBalancerInstanceHealth(
        string $elbName
    ): InstanceHealthCollection {
        $instanceHealth = $this->elbCli->describeInstanceHealth(
            [ "LoadBalancerName" => $elbName ]
        )->search("* | [0]");

        return ELBBuilder::buildInstanceHealth($instanceHealth);
    }

    /**
     * @return ElasticLoadBalancerCollection
     */
    public function retrieveElasticLoadBalancers(): ElasticLoadBalancerCollection
    {
        $elbs = $this->elbCli->describeLoadBalancers()
            ->search("* | [0]");

        return ELBBuilder::buildElasticLoadBalancer($elbs);
    }

    /**
     * @param string $groupName
     * @return ActivityCollection
     */
    public function retrieveAutoScalingActivities(string $groupName = null): ActivityCollection
    {
        $params = [ "MaxRecords" => 50 ];

        (empty($groupName)) ?: $params["AutoScalingGroupName"] = $groupName;

        $activities = $this->atScalingCli->describeScalingActivities($params)
            ->search("* | [0]");

        return AutoScalingBuilder::buildActivities($activities);
    }

    /**
     * @return AutoScalingGroupCollection
     */
    public function retrieveAutoScalingGroups(): AutoScalingGroupCollection
    {
        $groups = $this->atScalingCli->describeAutoScalingGroups()
            ->search("* | [0]");

        return AutoScalingBuilder::buildAutoScalingGroups(
            $groups
        );
    }

    /**
     * @param EC2InstanceCollection|null $collection
     * @param string|null $instanceId
     * @return EC2InstanceCollection
     */
    public function retrieveInstances(
        EC2InstanceCollection $collection = null,
        string $instanceId = null
    ): EC2InstanceCollection {
        $parameters = is_null($instanceId)
            ? [ 'MaxResults' => 100 ]
            : ['InstanceIds' => [$instanceId]];

        $instances = [];

        do {
            $response = $this->ec2Cli->describeInstances($parameters);
            $instances = array_merge(
                $instances,
                $response->search('Reservations[].Instances[]')
            );

            $parameters['NextToken'] = $response['NextToken'];
        } while($response['NextToken']);


        return InstanceBuilder::build($instances, $collection);
    }

    /**
     * @return SubnetCollection
     */
    public function retrieveSubnets(): SubnetCollection
    {
        $subnets = $this->ec2Cli->describeSubnets()
            ->search('* | [0]');

        return SubnetBuilder::build($subnets);
    }

    /**
     * @return ReservationCollection
     */
    public function retrieveReservedInstances(): ReservationCollection
    {
        $reserves = $this->ec2Cli->describeReservedInstances()
            ->search("* | [0]");

        return ReservationBuilder::build($reserves);
    }

    /**
     * @param array $filter
     * @return VolumeCollection
     */
    public function retrieveVolumes(array $filter = []): VolumeCollection
    {
        $volumes = $this->ec2Cli->describeVolumes($filter)
            ->search('* | [0]');

        return VolumeBuilder::build($volumes);
    }

    /**
     * @inheritdoc
     */
    public function checkCredential(): bool
    {
        $this->retrieveInstances();
        return true;
    }
}
