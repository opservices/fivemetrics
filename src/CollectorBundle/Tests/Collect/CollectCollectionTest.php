<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 24/11/17
 * Time: 09:50
 */

namespace CollectorBundle\Tests\Collect;

use CollectorBundle\Collect\Collect;
use CollectorBundle\Collect\CollectCollection;
use PHPUnit\Framework\TestCase;

/**
 * Class CollectCollectionTest
 * @package CollectorBundle\Tests\Collect
 */
class CollectCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function findByRemovedElement()
    {
        $collection = new CollectCollection([
            Collect::selfBuild(1, 'test', 1, 300)
        ]);

        $this->assertInstanceOf(
            Collect::class,
            $collection->find('1')
        );

        $collection->remove('1');

        $this->assertNull($collection->find('1'));
    }
}
