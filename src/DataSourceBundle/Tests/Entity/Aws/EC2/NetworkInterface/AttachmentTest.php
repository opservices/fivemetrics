<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 17/02/17
 * Time: 08:05
 */

namespace DataSourceBundle\Tests\Entity\Aws\EC2\NetworkInterface;

use DataSourceBundle\Entity\Aws\EC2\NetworkInterface\Attachment;
use EssentialsBundle\Entity\DateTime\DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Class AttachmentTest
 * @package DataSourceBundle\Tests\Entity\Aws\EC2\NetworkInterface
 */
class AttachmentTest extends TestCase
{
    /**
     * @var Attachment
     */
    protected $attachment;

    public function setUp()
    {
        $this->attachment = new Attachment(
            'attachmentId',
            1,
            'attaching',
            DateTime::createFromFormat('Y-m-d H:i', '2017-02-17 08:09'),
            false
        );
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals(
            'attachmentId',
            $this->attachment->getAttachmentId()
        );

        $this->assertEquals(
            1,
            $this->attachment->getDeviceIndex()
        );

        $this->assertEquals(
            'attaching',
            $this->attachment->getStatus()
        );

        $this->assertEquals(
            '2017-02-17 08:09',
            $this->attachment->getAttachTime()->format('Y-m-d H:i')
        );

        $this->assertFalse($this->attachment->isDeleteOnTermination());
    }

    /**
     * @test
     */
    public function setAttachmentId()
    {
        $this->attachment->setAttachmentId('attachmentId.test');

        $this->assertEquals(
            'attachmentId.test',
            $this->attachment->getAttachmentId()
        );
    }

    /**
     * @test
     */
    public function setDeviceIndex()
    {
        $this->attachment->setDeviceIndex(2);

        $this->assertEquals(
            2,
            $this->attachment->getDeviceIndex()
        );
    }

    /**
     * @test
     */
    public function setStatus()
    {
        $this->attachment->setStatus('attached');

        $this->assertEquals(
            'attached',
            $this->attachment->getStatus()
        );
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function setInvalidStatus()
    {
        $this->attachment->setStatus('test');
    }

    /**
     * @test
     */
    public function setAttachTime()
    {
        $this->attachment->setAttachTime(
            DateTime::createFromFormat('Y-m-d H:i', '2017-02-17 08:43')
        );

        $this->assertEquals(
            '2017-02-17 08:43',
            $this->attachment->getAttachTime()->format('Y-m-d H:i')
        );
    }

    /**
     * @test
     */
    public function setDeleteOnTermination()
    {
        $this->attachment->setDeleteOnTermination(true);
        $this->assertTrue($this->attachment->isDeleteOnTermination());
    }
}
