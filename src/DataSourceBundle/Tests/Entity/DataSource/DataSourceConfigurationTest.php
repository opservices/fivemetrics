<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 21/09/17
 * Time: 09:32
 */

namespace DataSourceBundle\Tests\Entity\DataSource;

use DataSourceBundle\Entity\DataSource\DataSource;
use DataSourceBundle\Entity\DataSource\DataSourceConfiguration;
use PHPUnit\Framework\TestCase;

class DataSourceConfigurationTest extends TestCase
{
    /**
     * @var DataSourceConfiguration
     */
    protected $dsConf;

    public function setUp()
    {
        $this->dsConf = new DataSourceConfiguration(
            new DataSource('test.unit'),
            60
        );
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals(
            new DataSource('test.unit'),
            $this->dsConf->getDataSource()
        );

        $this->assertEquals(
            60,
            $this->dsConf->getCollectInterval()
        );
    }

    /**
     * @test
     * @dataProvider invalidIntervalProvider
     * @expectedException \InvalidArgumentException
     */
    public function setInvalidCollectInterval($interval)
    {
        $this->dsConf->setCollectInterval($interval);
    }

    public function invalidIntervalProvider()
    {
        return [
            [ 0 ],
            [ -1 ],
        ];
    }
}
