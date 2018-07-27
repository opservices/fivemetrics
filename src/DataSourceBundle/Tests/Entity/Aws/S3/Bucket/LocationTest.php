<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 7/11/17
 * Time: 10:56 AM
 */

namespace DataSourceBundle\Tests\Entity\Aws\S3\Bucket;

use DataSourceBundle\Entity\Aws\S3\Bucket\Location;
use PHPUnit\Framework\TestCase;

/**
 * Class LocationTest
 * @package DataSourceBundle\Tests\Entity\Aws\S3\Bucket
 */
class LocationTest extends TestCase
{
    /**
     * @var Location
     */
    protected $location;

    public function setUp()
    {
        $this->location = new Location('us-east-2');
    }

    /**
     * @test
     */
    public function validLocation()
    {
        $this->assertEquals("us-east-2", $this->location->getLocationConstraint());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function invalidLocation()
    {
        $this->location->setLocationConstraint('');
        $this->expectException('InvalidArgumentException');
    }
}
