<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 21/01/17
 * Time: 11:44
 */

namespace DataSourceBundle\Entity\Aws\EC2\Instance;

use DataSourceBundle\Collection\Aws\EC2\BlockDeviceMappingCollection;
use DataSourceBundle\Collection\Aws\EC2\NetworkInterfaceCollection;
use DataSourceBundle\Collection\Aws\EC2\PrivateIpAddressCollection;
use DataSourceBundle\Collection\Aws\EC2\ProductCodeCollection;
use DataSourceBundle\Collection\Aws\EC2\SecurityGroupCollection;
use DataSourceBundle\Collection\Aws\EC2\Instance\InstanceCollection;
use DataSourceBundle\Entity\Aws\EC2\NetworkInterface\Association;
use DataSourceBundle\Entity\Aws\EC2\NetworkInterface\Attachment;
use DataSourceBundle\Entity\Aws\EC2\NetworkInterface\NetworkInterface;
use DataSourceBundle\Entity\Aws\EC2\NetworkInterface\PrivateIpAddress;
use EssentialsBundle\Entity\DateTime\DateTime;
use DataSourceBundle\Entity\Aws\EC2\SecurityGroup\SecurityGroup;
use DataSourceBundle\Entity\Aws\Tag\Builder as TagsBuilder;

/**
 * Class Builder
 * @package Entity\Aws\EC2\Instance
 */
class Builder
{
    /**
     * @param array $data
     * @param InstanceCollection|null $instances
     * @return InstanceCollection
     */
    public static function build(
        array $data,
        InstanceCollection $instances = null
    ): InstanceCollection {

        if (is_null($instances)) {
            $instances = new InstanceCollection();
        }

        foreach ($data as $ec2) {
            $instances->add(
                new Instance(
                    $ec2['InstanceId'],
                    $ec2['ImageId'],
                    $ec2['PrivateDnsName'],
                    $ec2['PublicDnsName'],
                    $ec2['StateTransitionReason'],
                    $ec2['AmiLaunchIndex'],
                    $ec2['InstanceType'],
                    $ec2['Architecture'],
                    $ec2['RootDeviceType'],
                    $ec2['RootDeviceName'],
                    $ec2['VirtualizationType'],
                    $ec2['ClientToken'],
                    $ec2['Hypervisor'],
                    $ec2['EbsOptimized'],
                    new DateTime($ec2['LaunchTime']),
                    self::buildMonitoring($ec2['Monitoring']),
                    self::buildPlacement($ec2['Placement']),
                    self::buildInstanceState($ec2['State']),
                    TagsBuilder::build($ec2['Tags']),
                    self::buildProductCodes($ec2['ProductCodes']),
                    self::buildBlockDeviceMappingCollection($ec2['BlockDeviceMappings']),
                    self::buildSecurityGroupCollection($ec2['SecurityGroups']),
                    self::buildNetworkInterfaceCollection($ec2['NetworkInterfaces']),
                    $ec2['SriovNetSupport'],
                    $ec2['SpotInstanceRequestId'],
                    $ec2['RamdiskId'],
                    $ec2['PublicIpAddress'],
                    $ec2['Platform'],
                    $ec2['KernelId'],
                    $ec2['InstanceLifecycle'],
                    (!!$ec2['EnaSupport']),
                    $ec2['KeyName'],
                    $ec2['SubnetId'],
                    $ec2['VpcId'],
                    $ec2['SourceDestCheck'],
                    $ec2['PrivateIpAddress'],
                    (empty($ec2['IamInstanceProfile']))
                        ? null
                        : self::buildIamInstanceProfile($ec2['IamInstanceProfile']),
                    (empty($ec2['StateReason'])) ? null : self::buildStateReason($ec2['StateReason'])
                )
            );
        }

        return $instances;
    }

    /**
     * @param array $monitoring
     * @return Monitoring
     */
    protected static function buildMonitoring(array $monitoring): Monitoring
    {
        return new Monitoring($monitoring['State']);
    }

    /**
     * @param array $iamInstanceProfile
     * @return IamInstanceProfile
     */
    protected static function buildIamInstanceProfile(
        array $iamInstanceProfile
    ): IamInstanceProfile {
        return new IamInstanceProfile(
            $iamInstanceProfile['Arn'],
            $iamInstanceProfile['Id']
        );
    }

    /**
     * @param array $state
     * @return InstanceState
     */
    protected static function buildInstanceState(array $state): InstanceState
    {
        return new InstanceState($state['Code'], $state['Name']);
    }

    /**
     * @param array $productCodes
     * @return ProductCodeCollection
     */
    protected static function buildProductCodes(array $productCodes): ProductCodeCollection
    {
        $productCodeCollection = new ProductCodeCollection();
        foreach ($productCodes as $pc) {
            $productCodeCollection->add(
                new ProductCode(
                    $pc['ProductCodeId'],
                    $pc['ProductCodeType']
                )
            );
        }
        return $productCodeCollection;
    }

    /**
     * @param array $placement
     * @return Placement
     */
    protected static function buildPlacement(array $placement): Placement
    {
        return new Placement(
            $placement['AvailabilityZone'],
            $placement['Tenancy'],
            $placement['GroupName'],
            $placement['HostId'],
            $placement['Affinity']
        );
    }

    /**
     * @param array $stateReason
     * @return StateReason
     */
    protected static function buildStateReason(array $stateReason): StateReason
    {
        return new StateReason(
            $stateReason['Code'],
            $stateReason['Message']
        );
    }

    /**
     * @param array $blockDeviceMappings
     * @return BlockDeviceMappingCollection
     */
    protected static function buildBlockDeviceMappingCollection(
        array $blockDeviceMappings
    ): BlockDeviceMappingCollection {
        $blockDeviceMappingCollection = new BlockDeviceMappingCollection();

        foreach ($blockDeviceMappings as $bd) {
            $blockDeviceMappingCollection->add(
                new BlockDeviceMapping(
                    $bd['DeviceName'],
                    new Ebs(
                        new DateTime($bd['Ebs']['AttachTime']),
                        $bd['Ebs']['DeleteOnTermination'],
                        $bd['Ebs']['VolumeId'],
                        $bd['Ebs']['Status']
                    )
                )
            );
        }

        return $blockDeviceMappingCollection;
    }

    /**
     * @param array $securityGroups
     * @return SecurityGroupCollection
     */
    protected static function buildSecurityGroupCollection(
        array $securityGroups = null
    ): SecurityGroupCollection {
        $securityGroupCollection = new SecurityGroupCollection();

        foreach ($securityGroups as $sg) {
            $securityGroupCollection->add(
                new SecurityGroup($sg['GroupName'], $sg['GroupId'])
            );
        }

        return $securityGroupCollection;
    }

    /**
     * @param array $networkInterfaces
     * @return NetworkInterfaceCollection
     */
    protected static function buildNetworkInterfaceCollection(
        array $networkInterfaces
    ): NetworkInterfaceCollection {
        $networkInterfaceCollection = new NetworkInterfaceCollection();
        foreach ($networkInterfaces as $networkInterface) {
            $networkInterfaceCollection->add(
                new NetworkInterface(
                    $networkInterface['NetworkInterfaceId'],
                    (string) $networkInterface['SubnetId'],
                    (string) $networkInterface['VpcId'],
                    (string) $networkInterface['Description'],
                    $networkInterface['OwnerId'],
                    $networkInterface['Status'],
                    (string) $networkInterface['MacAddress'],
                    (string) $networkInterface['PrivateIpAddress'],
                    (string) $networkInterface['SourceDestCheck'],
                    self::buildSecurityGroupCollection($networkInterface['Groups']),
                    self::buildAttachment($networkInterface['Attachment']),
                    self::buildPrivateIpAddress($networkInterface['PrivateIpAddresses']),
                    (empty($networkInterface['Association']))
                        ? null
                        : self::buildAssociation($networkInterface['Association']),
                    $networkInterface['PrivateDnsName']
                )
            );
        }

        return $networkInterfaceCollection;
    }

    /**
     * @param array $attachment
     * @return Attachment
     */
    protected static function buildAttachment(array $attachment): Attachment
    {
        return new Attachment(
            $attachment['AttachmentId'],
            $attachment['DeviceIndex'],
            $attachment['Status'],
            new DateTime($attachment['AttachTime']),
            $attachment['DeleteOnTermination']
        );
    }

    /**
     * @param array $association
     * @return Association
     */
    protected static function buildAssociation(array $association): Association
    {
        return new Association(
            $association['PublicIp'],
            $association['PublicDnsName'],
            $association['IpOwnerId']
        );
    }

    /**
     * @param array $privateIpAddress
     * @return PrivateIpAddressCollection
     */
    protected static function buildPrivateIpAddress(
        array $privateIpAddress = null
    ): PrivateIpAddressCollection {
        $privateIpAddressCollection = new PrivateIpAddressCollection();

        foreach ($privateIpAddress as $privateIp) {
            $privateIpAddressCollection->add(
                new PrivateIpAddress(
                    $privateIp['PrivateIpAddress'],
                    $privateIp['Primary'],
                    $privateIp['PrivateDnsName'],
                    (empty($privateIp['Association'])) ? null : self::buildAssociation($privateIp['Association'])
                )
            );
        }

        return $privateIpAddressCollection;
    }
}
