<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 8/16/17
 * Time: 3:19 PM
 */

namespace DataSourceBundle\Tests\Collection\Aws\EC2\EBS;

use DataSourceBundle\Collection\Aws\EBS\Attachment\AttachmentCollection;
use DataSourceBundle\Entity\Aws\EBS\Attachment\Attachment;
use EssentialsBundle\Entity\DateTime\DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Class AttachmentCollectionTest
 * @package DataSourceBundle\Tests\Collection\Aws\EC2\EBS
 */
class AttachmentCollectionTest extends TestCase
{
    /**
     * @var AttachmentCollection
     */
    protected $attachmentCollection;

    public function setUp()
    {
        $this->attachmentCollection = new AttachmentCollection();
    }

    /**
     * @test
     */
    public function addVolume()
    {
        $this->attachmentCollection->add(
            new Attachment(
                new DateTime(),
                'instance-id',
                'volume-id',
                'attached',
                true,
                '/dev/sda'
            )
        );

        foreach ($this->attachmentCollection as $attachment) {
            $this->assertInstanceOf(Attachment::class, $attachment);
        }

        $this->assertEquals(
            1,
            count($this->attachmentCollection)
        );
    }
}
