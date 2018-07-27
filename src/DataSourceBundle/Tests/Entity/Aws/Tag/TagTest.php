<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 16/02/17
 * Time: 16:43
 */

namespace DataSourceBundle\Tests\Entity\Aws\Common\Tag;

use DataSourceBundle\Entity\Aws\Tag\Tag;
use PHPUnit\Framework\TestCase;

/**
 * Class TagTest
 * @package DataSourceBundle\Tests\Entity\Aws\Common\Tag
 */
class TagTest extends TestCase
{
    /**
     * @var Tag
     */
    protected $tag;

    public function setUp()
    {
        $this->tag = new Tag("key", "");
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals(
            "key",
            $this->tag->getKey()
        );

        $this->assertEmpty($this->tag->getValue());
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
     * @expectedException \InvalidArgumentException
     */
    public function setInvalidKey()
    {
        $this->tag->setKey("");
    }

    /**
     * @test
     */
    public function setValue()
    {
        $this->tag->setValue("value");

        $this->assertEquals(
            "value",
            $this->tag->getValue()
        );
    }

    /**
     * @test
     */
    public function convertTagToArray()
    {
        $this->assertEquals(
            [ 'Key' => 'key', 'Value' => '' ],
            $this->tag->toArray()
        );
    }
}
