<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 8/16/17
 * Time: 5:48 PM
 */

namespace DataSourceBundle\Tests\Entity\Aws\EBS;

use DataSourceBundle\Entity\Aws\EBS\Volume\Builder;
use DataSourceBundle\Entity\Aws\EBS\Volume\Volume;
use EssentialsBundle\Entity\DateTime\DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Class BuilderTest
 * @package DataSourceBundle\Tests\Entity\Aws\EBS
 */
class BuilderTest extends TestCase
{
    /**
     * @test
     * @dataProvider getDataValidToVolumeProvider
     */
    public function volumeTest($data)
    {
        /**
         * @var $volume Volume
         */
        $volume = Builder::build([$data])->at(0);
        $this->assertEquals('in-use', $volume->getState());
        $volume->setState('available');
        $this->assertEquals('gp2', $volume->getVolumeType());
        $volume->setVolumeType('standard');
        $this->assertEquals('standard', $volume->getVolumeType());
        $volume->setAvailabilityZone('us-east-1a');
        $this->assertFalse($volume->isEncrypted());
        $volume->setEncrypted(true);
        $this->assertTrue($volume->isEncrypted());
        $this->assertEquals('available', $volume->getState());
        $this->assertEquals('idsfs', $volume->getVolumeId());
        $volume->setVolumeId('bal2');
        $this->assertEquals('bal2', $volume->getVolumeId());
        $this->assertEquals(300, $volume->getIops());
        $volume->setIops(200);
        $this->assertEquals(200, $volume->getIops());
        $this->assertEquals(200, $volume->getSize());
        $volume->setSize(250);
        $this->assertEquals(250, $volume->getSize());
        $this->assertEquals('blabla', $volume->getSnapshotId());
        $this->assertEquals('us-east-1a', $volume->getAvailabilityZone());
        $this->assertEquals('bla', $volume->getKmsKeyId());
        $this->assertEquals(new DateTime("2017-08-07T15:47:30.119Z"), $volume->getCreateTime());
        $volume->setCreateTime(new DateTime("2017-08-07T15:48:30.119Z"));
        $this->assertEquals(new DateTime("2017-08-07T15:48:30.119Z"), $volume->getCreateTime());
        $this->assertGreaterThan(0, count($volume->getTags()));
        $this->assertGreaterThan(0, count($volume->getAttachments()));
    }

    /**
     * @test
     * @dataProvider getDataValidToVolumeProvider
     * @expectedException \InvalidArgumentException
     */
    public function setInvalidState($data)
    {
        $volume = Builder::build([$data])->at(0);
        $volume->setState('invalid');
    }

    /**
     * @test
     * @dataProvider getDataValidToVolumeProvider
     * @expectedException \InvalidArgumentException
     */
    public function setInvalidVolumeType($data)
    {
        $volume = Builder::build([$data])->at(0);
        $volume->setVolumeType('invalid');
    }

    public function getDataValidToVolumeProvider()
    {
        $tags = [
            [
                'Key' => 'foo',
                'Value' => 'bar'
            ]
        ];

        $attachments = [
            [
                'AttachTime' => '2017-08-07T15:47:30.119Z',
                    'DeleteOnTermination' => true,
                    'Device' => '/dev/sda',
                    'InstanceId' => 'idididid',
                    'State' => 'attached',
                    'VolumeId' => 'idididiidididi'
            ]
        ];

        $data = [
            [
                'AvailabilityZone' => 'us-east-1b',
                'CreateTime' => "2017-08-07T15:47:30.119Z",
                'VolumeType' => "gp2",
                'Encrypted' => false,
                'Iops' => 300,
                'KmsKeyId' => 'bla',
                'Size' => 200,
                'SnapshotId' => 'blabla',
                'State' => 'in-use',
                'Tags' => $tags,
                'VolumeId' => 'idsfs',
                'Attachments' => $attachments
            ]
        ];
        return [$data];
    }
}
