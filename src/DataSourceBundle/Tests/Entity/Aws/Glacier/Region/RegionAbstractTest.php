<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 8/3/17
 * Time: 3:23 PM
 */

namespace DataSourceBundle\Tests\Entity\Aws\Glacier\Region;

use DataSourceBundle\Entity\Aws\Region\RegionAbstract;
use EssentialsBundle\Reflection;
use PHPUnit\Framework\TestCase;

class RegionTest extends RegionAbstract
{
    public function __construct()
    {
        parent::__construct("unit-test", "Test");
    }
}

/**
 * Class AbstractRegionTest
 * @package Tests\Region
 */
class RegionAbstractTest extends TestCase
{
    protected $region;

    public function setUp()
    {
        $this->region = new RegionTest();
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function trySetAnInvalidRegionCode()
    {
        Reflection::callMethodOnObject($this->region, "setCode", [""]);
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function trySetAnInvalidRegionName()
    {
        Reflection::callMethodOnObject($this->region, "setName", [""]);
    }
}