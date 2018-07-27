<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 20/03/17
 * Time: 14:46
 */

namespace EssentialsBundle\Tests\Collection\Id;

use EssentialsBundle\Collection\Id\IdCollection;
use EssentialsBundle\Entity\Id\Id;
use PHPUnit\Framework\TestCase;

/**
 * Class IdCollectionTest
 * @package EssentialsBundle\Test\Collection\Id
 */
class IdCollectionTest extends TestCase
{
    /**
     * @var IdCollection
     */
    protected $ids;

    public function setUp()
    {
        $this->ids = new IdCollection(
            [ new Id('test'), new Id('unit') ]
        );
    }

    /**
     * @test
     */
    public function toString()
    {
        $this->assertEquals(
            'test.unit',
            (string)$this->ids
        );
    }

    /**
     * @param $invalidValue
     * @test
     * @dataProvider invalidIds
     * @expectedException \InvalidArgumentException
     */
    public function setInvalidId($invalidValue)
    {
        new Id($invalidValue);
    }

    public function invalidIds(): array
    {
        return [
            [ '' ],
            [ ' ' ],
            [ '[' ],
            [ ']' ],
            [ '[]' ],
            [ '{' ],
            [ '}' ],
            [ '{}' ],
            [ '´' ],
            [ '^' ],
            [ '~' ],
        ];
    }
}
