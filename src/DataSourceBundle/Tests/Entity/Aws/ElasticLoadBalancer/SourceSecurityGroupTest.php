<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 15/02/17
 * Time: 18:17
 */

namespace DataSourceBundle\Tests\Entity\Aws\ElasticLoadBalancer;

use DataSourceBundle\Entity\Aws\ElasticLoadBalancer\SourceSecurityGroup;
use PHPUnit\Framework\TestCase;

/**
 * Class SourceSecurityGroupTest
 * @package DataSourceBundle\Tests\Entity\Aws\ElasticLoadBalancer
 */
class SourceSecurityGroupTest extends TestCase
{
    /**
     * @var SourceSecurityGroup
     */
    protected $sg;

    public function setUp()
    {
        $this->sg = new SourceSecurityGroup("test", "unit test");
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals(
            "test",
            $this->sg->getOwnerAlias()
        );

        $this->assertEquals(
            "unit test",
            $this->sg->getGroupName()
        );
    }

    /**
     * @test
     */
    public function changeValues()
    {
        $this->sg->setOwnerAlias("b");
        $this->sg->setGroupName("c");

        $this->assertEquals(
            "b",
            $this->sg->getOwnerAlias()
        );

        $this->assertEquals(
            "c",
            $this->sg->getGroupName()
        );
    }
}
