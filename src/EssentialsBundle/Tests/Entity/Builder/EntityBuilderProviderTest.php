<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 23/11/17
 * Time: 08:12
 */

namespace EssentialsBundle\Tests\Entity\Builder;

use EssentialsBundle\Entity\Builder\EntityBuilderProvider;
use PHPUnit\Framework\TestCase;

class EntityBuilderProviderTest extends TestCase
{
    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function tryGetInvalidBuilder()
    {
        EntityBuilderProvider::factory('fakeClass');
    }
}
