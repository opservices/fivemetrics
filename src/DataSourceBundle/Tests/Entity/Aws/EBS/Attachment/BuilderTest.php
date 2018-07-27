<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 8/16/17
 * Time: 6:44 PM
 */

namespace DataSourceBundle\Tests\Entity\Aws\EBS\Attachment;

use DataSourceBundle\Entity\Aws\EBS\Attachment\Attachment;
use DataSourceBundle\Entity\Aws\EBS\Attachment\Builder;
use EssentialsBundle\Entity\DateTime\DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Class BuilderTest
 * @package DataSourceBundle\Tests\Entity\Aws\EBS\Attachment
 */
class BuilderTest extends TestCase
{

    /**
     * @test
     * @dataProvider getDataValidToAttachmentProvider
     */
    public function attachmentTest($data)
    {
        /**
         * @var $attachment Attachment
         */
        $attachment = Builder::build([$data])->at(0);
        $this->assertEquals('attached', $attachment->getState());
        $attachment->setState('attaching');
        $this->assertEquals('attaching', $attachment->getState());
        $this->assertTrue($attachment->isDeleteOnTermination());
        $attachment->setDeleteOnTermination(false);
        $this->assertFalse($attachment->isDeleteOnTermination());
        $this->assertEquals(new DateTime("2017-08-07T15:47:30.119Z"), $attachment->getAttachTime());
        $attachment->setAttachTime(new DateTime("2017-08-07T15:48:30.119Z"));
        $this->assertEquals(new DateTime("2017-08-07T15:48:30.119Z"), $attachment->getAttachTime());
        $this->assertEquals('idididiidididi', $attachment->getVolumeId());
        $attachment->setVolumeId('bal2');
        $this->assertEquals('bal2', $attachment->getVolumeId());
        $this->assertEquals('idididid', $attachment->getInstanceId());
        $attachment->setInstanceId('bal2');
        $this->assertEquals('bal2', $attachment->getInstanceId());
        $this->assertEquals('/dev/sda', $attachment->getDevice());
        $attachment->setDevice('/dev/sdb');
        $this->assertEquals('/dev/sdb', $attachment->getDevice());
    }

    /**
     * @test
     * @dataProvider getDataValidToAttachmentProvider
     * @expectedException \InvalidArgumentException
     */
    public function setInvalidVolumeType($data)
    {
        $volume = Builder::build([$data])->at(0);
        $volume->setState('invalid');
    }

    public function getDataValidToAttachmentProvider()
    {
        $data = [
            [
                'AttachTime' => '2017-08-07T15:47:30.119Z',
                'DeleteOnTermination' => true,
                'Device' => '/dev/sda',
                'InstanceId' => 'idididid',
                'State' => 'attached',
                'VolumeId' => 'idididiidididi'
            ]
        ];
        return [$data];
    }
}
