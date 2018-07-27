<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 16/02/17
 * Time: 17:00
 */

namespace DataSourceBundle\Tests\Entity\Aws\CloudWatch\Region;

use DataSourceBundle\Entity\Aws\CloudWatch\Region\RegionProvider;
use PHPUnit\Framework\TestCase;

/**
 * Class RegionProviderTest
 * @package Test\Entity\Aws\CloudWatch\Region
 */
class RegionProviderTest extends TestCase
{
    /**
     * @var RegionProvider
     */
    protected $rp;

    public function setUp()
    {
        $this->rp = new RegionProvider();
    }

    /**
     * @test
     * @dataProvider getRegions
     */
    public function allRegionsImplementsRegionInterface($region)
    {
        $this->assertInstanceOf(
            'DataSourceBundle\Entity\Aws\Region\RegionInterface',
            $this->rp->factory($region)
        );
    }

    public function getRegions()
    {
        return array_map(
            function ($region) {
                return [$region];
            },
            array_keys(RegionProvider::REGIONS)
        );
    }

    /**
     * @test
     * @dataProvider getInvalidRegions
     * @expectedException \InvalidArgumentException
     */
    public function tryRetrieveAnInvalidRegion($region)
    {
        $this->rp->factory($region);
    }

    public function getInvalidRegions()
    {
        return [
            [""],
            ["Vila Nova"]
        ];
    }

    /**
     * @test
     */
    public function retrieveValidRegions()
    {
        $this->assertEquals(
            array_keys(RegionProvider::REGIONS),
            $this->rp->listAvailableRegions()
        );
    }

    /**
     * @test
     */
    public function retrieveValidRegionsFullData()
    {
        $data = [];
        foreach (RegionProvider::REGIONS as $code => $class) {
            $region = $this->rp->factory($code);
            $data[] = [
                "name"  => $region->getName(),
                "code"    => $region->getCode()
            ];
        }

        $this->assertEquals($data, $this->rp->listAvailableRegions(true));
    }
}
