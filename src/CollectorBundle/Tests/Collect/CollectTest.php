<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 26/09/17
 * Time: 08:23
 */

namespace CollectorBundle\Tests\Collect;

use CollectorBundle\Collect\Collect;
use CollectorBundle\Collect\DataSource;
use CollectorBundle\Collect\ParameterCollection;
use EssentialsBundle\Entity\DateTime\DateTime;
use PHPUnit\Framework\TestCase;

class CollectTest extends TestCase
{
    /**
     * @var Collect
     */
    protected $collect;

    public function setUp()
    {
        $this->collect = new Collect(
            1,
            new DataSource('test', 1, 300),
            new ParameterCollection(),
            false,
            DateTime::createFromFormat('Y-m-d H:i:s', '2017-09-26 08:30:00')
        );
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals(1, $this->collect->getId());
        $this->assertEquals(
            new DataSource('test', 1, 300),
            $this->collect->getDataSource()
        );
        $this->assertEquals(
            new ParameterCollection(),
            $this->collect->getParameters()
        );
        $this->assertFalse($this->collect->isEnabled());
        $this->assertEquals(
            '2017-09-26 08:30:00',
            $this->collect->getLastUpdate()->format('Y-m-d H:i:s')
        );
        $this->assertCount(0, $this->collect->getPendingJobs());
    }
}
