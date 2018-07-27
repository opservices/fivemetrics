<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 16/02/17
 * Time: 08:33
 */

namespace DataSourceBundle\Tests\Entity\Aws\AutoScaling;

use DataSourceBundle\Entity\Aws\AutoScaling\Tag;
use PHPUnit\Framework\TestCase;

/**
 * Class TagTest
 * @package Test\Entity\Aws\AutoScaling
 */
class TagTest extends TestCase
{
    /**
     * @var Tag
     */
    protected $tag;

    public function setUp()
    {
        $this->tag = new Tag(
            "resourceId",
            "resourceType",
            true,
            "key",
            "value"
        );
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals(
            "resourceId",
            $this->tag->getResourceId()
        );

        $this->assertEquals(
            "resourceType",
            $this->tag->getResourceType()
        );

        $this->assertTrue($this->tag->isPropagateAtLaunch());

        $this->assertEquals(
            "key",
            $this->tag->getKey()
        );

        $this->assertEquals(
            "value",
            $this->tag->getValue()
        );
    }

    /**
     * @test
     */
    public function setResourceId()
    {
        $this->tag->setResourceId("resourceId.test");
        $this->assertEquals(
            "resourceId.test",
            $this->tag->getResourceId()
        );
    }

    /**
     * @test
     */
    public function setResourceType()
    {
        $this->tag->setResourceType("resourceType.test");
        $this->assertEquals(
            "resourceType.test",
            $this->tag->getResourceType()
        );
    }

    /**
     * @test
     */
    public function setPropagateAtLaunch()
    {
        $this->tag->setPropagateAtLaunch(false);
        $this->assertFalse($this->tag->isPropagateAtLaunch());
    }

    /**
     * @test
     */
    public function setKey()
    {
        $this->tag->setKey("key.test");
        $this->assertEquals(
            "key.test",
            $this->tag->getKey()
        );
    }
    /**
     * @test
     */
    public function setValue()
    {
        $this->tag->setValue("value.test");
        $this->assertEquals(
            "value.test",
            $this->tag->getValue()
        );
    }
}
