<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 11/08/17
 * Time: 15:15
 */

namespace DatabaseBundle\Tests\DataFixtures\NoSql\Configuration;

use DatabaseBundle\DataFixtures\NoSql\Configuration\Value;
use PHPUnit\Framework\TestCase;

class ValueTest extends TestCase
{
    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function createValueInstanceWithInvalidType()
    {
        new Value('test', 1);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @dataProvider invalidDataTypeProvider
     */
    public function createValueInstanceWithInvalidData($type, $data)
    {
        new Value($type, $data);
    }

    public function invalidDataTypeProvider()
    {
        return [
            [ 'fixed', [ 1 ] ],
            [ 'random', 1 ],
            [ 'random', '1' ],
        ];
    }
}
