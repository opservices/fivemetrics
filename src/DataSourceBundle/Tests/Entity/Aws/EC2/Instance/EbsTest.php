<?php
/**
 * Created by PhpStorm.
 * User: flunardelli
 * Date: 15/02/17
 * Time: 12:05
 */

namespace DataSourceBundle\Tests\Entity\Aws\EC2\Instance;

use DataSourceBundle\Entity\Aws\EC2\Instance\Ebs;
use EssentialsBundle\Entity\DateTime\DateTime;
use PHPUnit\Framework\TestCase;

class EbsTest extends TestCase
{
    /**
     * @var Ebs
     */
    protected $instance;

    /**
     * @var DateTime
     */
    protected $datetime;

    public function setUp()
    {
        $this->datetime = new DateTime();
        $this->instance = new Ebs($this->datetime, true, 'id', 'attached');
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals(
            "id",
            $this->instance->getVolumeId()
        );
        $this->assertEquals(
            true,
            $this->instance->isDeleteOnTermination()
        );
        $this->assertEquals(
            $this->datetime,
            $this->instance->getAttachTime()
        );
        $this->assertEquals(
            'attached',
            $this->instance->getStatus()
        );
    }

    /**
     * @test
     * @dataProvider getInvalidStatus
     * @expectedException \InvalidArgumentException
     */
    public function trySetStatus($status)
    {
        $this->instance->setStatus($status);
    }

    public function getInvalidStatus()
    {
        return [
            [""],
            ["invalid"]
        ];
    }


}