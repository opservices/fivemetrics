<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 8/16/17
 * Time: 3:04 PM
 */

namespace DataSourceBundle\Tests\Collection\Aws\EC2\EBS;

use function array_walk;
use DataSourceBundle\Collection\Aws\EBS\Attachment\AttachmentCollection;
use DataSourceBundle\Collection\Aws\EBS\Volume\VolumeCollection;
use DataSourceBundle\Collection\Aws\Tag\TagCollection;
use DataSourceBundle\Entity\Aws\EBS\Volume\Volume;
use EssentialsBundle\Entity\DateTime\DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Class EBSCollectionTest
 * @package DataSourceBundle\Tests\Collection\Aws\EC2\EBS
 */
class EBSCollectionTest extends TestCase
{
    /**
     * @var VolumeCollection
     */
    protected $volumeCollection;

    public function setUp()
    {
        $this->volumeCollection = new VolumeCollection();
    }

    /**
     * @test
     */
    public function addVolume()
    {
        $this->volumeCollection->add(
            new Volume(
                'us-east-1a',
                new DateTime(),
                false,
                300,
                null,
                200,
                null,
                'in-use',
                new TagCollection(),
                'id',
                'volume-id',
                new AttachmentCollection()
            )
        );

        foreach ($this->volumeCollection as $volume) {
            $this->assertInstanceOf(Volume::class, $volume);
        }

        $this->assertEquals(
            1,
            count($this->volumeCollection)
        );
    }
}
