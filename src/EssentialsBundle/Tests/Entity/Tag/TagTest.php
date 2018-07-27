<?php

namespace EssentialsBundle\Tests\Entity\Tag;

use EssentialsBundle\Entity\Tag\Tag;
use PHPUnit\Framework\TestCase;

/**
 * Class TagTest
 * @package EssentialsBundle\Tests\Entity\Tag
 */
class TagTest extends TestCase
{
    /**
     * @var Tag
     */
    protected $tag;

    public function setUp()
    {
        $this->tag = new Tag("testKey", "");
    }

    /**
     * @test
     */
    public function allTagsMustImplementsTagInterface()
    {
        $this->assertInstanceOf(
            'EssentialsBundle\Entity\Tag\TagInterface',
            $this->tag
        );
    }

    /**
     * @test
     */
    public function tryDefineValidKey()
    {
        $this->tag->setKey("keyName");

        $this->assertEquals(
            "keyName",
            $this->tag->getKey()
        );
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function tryDefineInvalidKey()
    {
        $this->tag->setKey("");
    }

    /**
     * @test
     */
    public function tryDefineValue()
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
    public function tagToString()
    {
        $this->assertEquals(
            'testKey:',
            (string)$this->tag
        );
    }
}
