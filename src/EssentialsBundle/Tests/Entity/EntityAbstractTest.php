<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 14/02/17
 * Time: 15:52
 */

namespace DataSourceBundle\Tests\Entity;

use EssentialsBundle\Entity\EntityAbstract;
use PHPUnit\Framework\TestCase;

/**
 * Class Entity
 * @package EssentialsBundle\Test\Entity
 */
class Entity extends EntityAbstract
{
    protected $array = [
        "string",
        20,
        0,
        -1,
        null,
        [ "string" ],
        []
    ];

    protected $string = "abc";

    protected $emptyString = "";

    protected $object;

    public function __construct()
    {
        $this->object = new \StdClass();

        $this->object->array = [ "string2", 1, 2 ];
        $this->object->string = "string3";
    }
}

/**
 * Class AbstractEntityTest
 * @package Test\Entity\Common
 */
class AbstractEntityTest extends TestCase
{
    /**
     * @var Entity
     */
    protected $entity;

    public function setUp()
    {
        $this->entity = new Entity();
    }

    /**
     * @test
     * @param $data
     * @dataProvider dataProvider
     */
    public function encodeObject($data)
    {
        $this->assertEquals(
            json_encode($data),
            json_encode($this->entity)
        );
    }

    /**
     * @test
     * @param $data
     * @dataProvider dataProvider
     */
    public function objectToArray($data)
    {
        $this->assertEquals(
            $data,
            $this->entity->toArray()
        );
    }

    public function dataProvider()
    {
        return [
            [
            json_decode(
                '{
                    "array": [
                        "string",
                        20,
                        0,
                        -1,
                        null,
                        [
                            "string"
                        ],
                        []
                    ],
                    "string": "abc",
                    "emptyString": "",
                    "object": {
                        "array": [
                             "string2",
                              1,
                              2
                        ],
                        "string": "string3"
                    }
                }',
                true
            )
            ]
        ];
    }
}
