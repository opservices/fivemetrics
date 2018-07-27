<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 26/09/17
 * Time: 14:35
 */

namespace CollectorBundle\Tests\Collect;

use CollectorBundle\Collect\Parameter;
use CollectorBundle\Collect\ParameterCollection;
use PHPUnit\Framework\TestCase;

class ParameterCollectionTest extends TestCase
{
    /**
     * @var ParameterCollection
     */
    protected $collection;

    public function setUp()
    {
        $this->collection = new ParameterCollection();
    }

    /**
     * @test
     */
    public function find()
    {
        $this->assertNull($this->collection->find('test'));

        $this->collection->add(new Parameter('test', 'unit'));
        $this->collection->add(new Parameter('111', '222'));

        $parameter = $this->collection->find('test');

        $this->assertCount(2, $this->collection);
        $this->assertInstanceOf(Parameter::class, $parameter);

        $this->collection->removeElement($parameter);

        $this->assertCount(1, $this->collection);
        $this->assertNull($this->collection->find('test'));
        $this->assertInstanceOf(
            Parameter::class,
            $this->collection->find('111')
        );
    }
}
