<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 20/03/17
 * Time: 15:17
 */

namespace DataSourceBundle\Tests\Gearman\Queue\Collector\Generic;

use DataSourceBundle\Gearman\Queue\Collector\Generic\Configuration;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Generic;
use PHPUnit\Framework\TestCase;

/**
 * Class GenericTest
 * @package DataSourceBundle\Tests\Gearman\Queue\Collector\Generic
 */
class GenericTest extends TestCase
{
    /**
     * @var Generic
     */
    protected $generic;

    public function setUp()
    {
        $this->generic = new Generic();
    }

    /**
     * @test
     */
    public function setConfiguration()
    {
        $confs = [
            'processors' => [
                [
                    'name' => 'test',
                    'maxRetries' => 5
                ]
            ]
        ];

        $this->generic->setConfiguration($confs);

        $this->assertEquals(
            Configuration::build($confs['processors']),
            $this->generic->getConfiguration()
        );
    }
}
