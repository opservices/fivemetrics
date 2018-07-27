<?php
namespace DataSourceBundle\Tests\Entity\Aws\EC2\SecurityGroup;

use DataSourceBundle\Entity\Aws\EC2\SecurityGroup\SecurityGroup;
use PHPUnit\Framework\TestCase;

class SecurityGroupTest extends TestCase
{
    /**
     * @var SecurityGroup
     */
    protected $securityGroupTest;

    public function setUp()
    {
        $this->securityGroupTest = new SecurityGroup('name', 'id');
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals(
            "name",
            $this->securityGroupTest->getGroupName()
        );
        $this->assertEquals(
            "id",
            $this->securityGroupTest->getGroupId()
        );
    }

    /**
     * @test
     */
    public function testGroupName()
    {
        $this->securityGroupTest->setGroupName("name");

        $this->assertEquals(
            "name",
            $this->securityGroupTest->getGroupName()
        );
    }

    /**
     * @test
     */
    public function testGroupId()
    {
        $this->securityGroupTest->setGroupId("id");

        $this->assertEquals(
            "id",
            $this->securityGroupTest->getGroupId()
        );
    }
}