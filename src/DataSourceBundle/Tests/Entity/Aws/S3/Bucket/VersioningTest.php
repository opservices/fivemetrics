<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 7/11/17
 * Time: 10:16 AM
 */

namespace DataSourceBundle\Tests\Entity\Aws\S3\Bucket;

use DataSourceBundle\Entity\Aws\S3\Bucket\Versioning;
use PHPUnit\Framework\TestCase;
use Psr\Log\InvalidArgumentException;

/**
 * Class VersioningTest
 * @package DataSourceBundle\Tests\Entity\Aws\S3\Bucket
 */
class VersioningTest extends TestCase
{
    /**
     * @var Versioning
     */
    protected $versioning;

    const STATUS_TYPES = [
        'Enabled',
        'Disabled',
        'Suspended'
    ];

    public function setUp()
    {
        $this->versioning = new Versioning("Enabled");
    }

    /**
     * @dataProvider statusProvider
     * @param $status
     * @test
     */
    public function getValidStatus($status)
    {
        $this->versioning->setStatus($status);
        $this->assertEquals($status, $this->versioning->getStatus());
    }

    public function statusProvider()
    {
        foreach (self::STATUS_TYPES as $status) {
            yield [$status];
        }
    }

    /**
     * @test
     */
    public function mfaDelete()
    {
        $this->versioning->setMFADelete('Enabled');
        $this->assertEquals('Enabled', $this->versioning->getMFADelete());
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function getInvalidStatus()
    {
        $this->versioning->setStatus("Invalid");
        $this->expectException("InvalidArgumentException");
    }
}
