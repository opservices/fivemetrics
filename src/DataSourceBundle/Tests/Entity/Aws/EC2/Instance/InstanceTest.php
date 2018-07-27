<?php
/**
 * Created by PhpStorm.
 * User: flunardelli
 * Date: 16/02/17
 * Time: 10:29
 */

namespace DataSourceBundle\Tests\Entity\Aws\EC2\Instance;

use DataSourceBundle\Collection\Aws\Tag\TagCollection;
use DataSourceBundle\Collection\Aws\EC2\BlockDeviceMappingCollection;
use DataSourceBundle\Collection\Aws\EC2\NetworkInterfaceCollection;
use DataSourceBundle\Collection\Aws\EC2\ProductCodeCollection;
use DataSourceBundle\Collection\Aws\EC2\SecurityGroupCollection;
use DataSourceBundle\Entity\Aws\Tag\Tag;
use DataSourceBundle\Entity\Aws\EC2\Instance\IamInstanceProfile;
use DataSourceBundle\Entity\Aws\EC2\Instance\Instance;
use DataSourceBundle\Entity\Aws\EC2\Instance\InstanceState;
use DataSourceBundle\Entity\Aws\EC2\Instance\Monitoring;
use DataSourceBundle\Entity\Aws\EC2\Instance\Placement;
use DataSourceBundle\Entity\Aws\EC2\Instance\ProductCode;
use DataSourceBundle\Entity\Aws\EC2\Instance\StateReason;
use EssentialsBundle\Entity\DateTime\DateTime;
use PHPUnit\Framework\TestCase;

class InstanceTest extends TestCase
{
    /**
     * @var Instance
     */
    protected $instance;

    public function setUp()
    {
        $this->instance = $this->getEC2InstanceTest();
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals(
            'kernelId',
            $this->instance->getKernelId()
        );

        $this->assertEquals(
            'instanceLifecycle',
            $this->instance->getInstanceLifecycle()
        );

        $this->assertEquals(
            new IamInstanceProfile('arn', 'id'),
            $this->instance->getIamInstanceProfile()
        );

        $this->assertEquals(
            'ami-6d1c2007',
            $this->instance->getImageId()
        );

        $this->assertEquals(
            'PrivateDnsName',
            $this->instance->getPrivateDnsName()
        );

        $this->assertEquals(
            'PublicDnsName',
            $this->instance->getPublicDnsName()
        );

        $this->assertEquals(
            'StateTransitionReason',
            $this->instance->getStateTransitionReason()
        );

        $this->assertEquals(
            'keyName',
            $this->instance->getKeyName()
        );

        $this->assertEquals(
            0,
            $this->instance->getAmiLaunchIndex()
        );

        $this->assertEquals(
            new ProductCodeCollection([
                new ProductCode('aw0evgkw8e5c1q413zgy5pjce', 'marketplace')
            ]),
            $this->instance->getProductCodes()
        );

        $this->assertEquals(
            DateTime::createFromFormat('Y-m-d H:i', '2017-03-21 09:52'),
            $this->instance->getLaunchTime()
        );

        $this->assertEquals(
            new Monitoring('disabled'),
            $this->instance->getMonitoring()
        );

        $this->assertEquals(
            'subnetId',
            $this->instance->getSubnetId()
        );

        $this->assertEquals(
            'vpcId',
            $this->instance->getVpcId()
        );

        $this->assertEquals(
            'privateIpAddress',
            $this->instance->getPrivateIpAddress()
        );

        $this->assertEquals(
            new StateReason('code', 'message'),
            $this->instance->getStateReason()
        );

        $this->assertEquals(
            '/dev/sda1',
            $this->instance->getRootDeviceName()
        );

        $this->assertEquals(
            new BlockDeviceMappingCollection(),
            $this->instance->getBlockDeviceMappings()
        );

        $this->assertEquals(
            '148217644108981449',
            $this->instance->getClientToken()
        );

        $this->assertEquals(
            new TagCollection([ new Tag('Name', 'test') ]),
            $this->instance->getTags()
        );

        $this->assertEquals(
            new SecurityGroupCollection(),
            $this->instance->getSecurityGroups()
        );

        $this->assertEquals(
            new NetworkInterfaceCollection(),
            $this->instance->getNetworkInterfaces()
        );

        $this->assertEquals(
            'hvm',
            $this->instance->getVirtualizationType()
        );

        $this->assertEquals(
            'ebs',
            $this->instance->getRootDeviceType()
        );

        $this->assertEquals(
            'xen',
            $this->instance->getHypervisor()
        );

        $this->assertEquals(
            'x86_64',
            $this->instance->getArchitecture()
        );

        $this->assertEquals(
            'spotInstanceRequestId',
            $this->instance->getSpotInstanceRequestId()
        );

        $this->assertEquals(
            'ramdiskId',
            $this->instance->getRamdiskId()
        );

        $this->assertEquals(
            'sriovNetSupport',
            $this->instance->getSriovNetSupport()
        );

        $this->assertEquals(
            'publicIpAddress',
            $this->instance->getPublicIpAddress()
        );

        $this->assertTrue($this->instance->isSourceDestCheck());
        $this->assertFalse($this->instance->isEbsOptimized());
        $this->assertFalse($this->instance->isEnaSupport());
    }

    public function getEC2InstanceTest()
    {
        return new Instance(
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
            new TagCollection([ new Tag('Name', 'test') ]),
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
        );
    }

    /**
     * @test
     */
    public function createBuilderInstance()
    {
        $this->assertInstanceOf(
            'DataSourceBundle\Entity\Aws\EC2\Instance\Instance',
            $this->instance
        );
    }

    public function instanceData()
    {
        $builder = new BuilderTest();
        return $builder->validELBsData();
    }

    /**
     * @test
     * @dataProvider getInvalidStatus
     * @expectedException \InvalidArgumentException
     */
    public function trySetVirtualizationType($status)
    {
        $this->instance->setVirtualizationType($status);
    }

    /**
     * @test
     * @dataProvider getInvalidStatus
     * @expectedException \InvalidArgumentException
     */
    public function trySetHypervisor($status)
    {
        $this->instance->setHypervisor($status);
    }

    /**
     * @test
     * @dataProvider getInvalidStatus
     * @expectedException \InvalidArgumentException
     */
    public function trySetArchitecture($status)
    {
        $this->instance->setArchitecture($status);
    }

    /**
     * @test
     * @dataProvider getInvalidStatus
     * @expectedException \InvalidArgumentException
     */
    public function trySetRootDeviceType($status)
    {
        $this->instance->setRootDeviceType($status);
    }

    public function getInvalidStatus()
    {
        return [
            [""],
            ["invalid"]
        ];
    }

}