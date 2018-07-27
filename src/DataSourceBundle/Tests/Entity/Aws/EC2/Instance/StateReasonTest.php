<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 17/02/17
 * Time: 14:04
 */

namespace DataSourceBundle\Tests\Entity\Aws\EC2\Instance;

use DataSourceBundle\Entity\Aws\EC2\Instance\StateReason;
use PHPUnit\Framework\TestCase;

/**
 * Class StateReasonTest
 * @package DataSourceBundle\Tests\Entity\Aws\EC2\Instance
 */
class StateReasonTest extends TestCase
{
    /**
     * @var StateReason
     */
    protected $stateReason;

    public function setUp()
    {
        $this->stateReason = new StateReason(
            'code',
            'message'
        );
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals(
            'code',
            $this->stateReason->getCode()
        );

        $this->assertEquals(
            'message',
            $this->stateReason->getMessage()
        );
    }

    /**
     * @test
     */
    public function setCode()
    {
        $this->stateReason->setCode('code.test');

        $this->assertEquals(
            'code.test',
            $this->stateReason->getCode()
        );
    }

    /**
     * @test
     */
    public function setMessage()
    {
        $this->stateReason->setMessage('message.test');

        $this->assertEquals(
            'message.test',
            $this->stateReason->getMessage()
        );
    }
}
