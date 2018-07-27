<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 21/02/17
 * Time: 17:07
 */

namespace DataSourceBundle\Tests\Entity\Aws\EC2\Instance;

use DataSourceBundle\Entity\Aws\EC2\Instance\IamInstanceProfile;
use PHPUnit\Framework\TestCase;

/**
 * Class IamInstanceProfileTest
 * @package DataSourceBundle\Test\Entity\Aws\EC2\Instance
 */
class IamInstanceProfileTest extends TestCase
{
    /**
     * @var IamInstanceProfile
     */
    protected $iam;

    public function setUp()
    {
        $this->iam = new IamInstanceProfile('arn', 'id');
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals(
            'arn',
            $this->iam->getArn()
        );

        $this->assertEquals(
            'id',
            $this->iam->getId()
        );
    }

    /**
     * @test
     */
    public function setArn()
    {
        $this->iam->setArn('arn.test');

        $this->assertEquals(
            'arn.test',
            $this->iam->getArn()
        );
    }

    /**
     * @test
     */
    public function setId()
    {
        $this->iam->setId('id.test');

        $this->assertEquals(
            'id.test',
            $this->iam->getId()
        );
    }
}
