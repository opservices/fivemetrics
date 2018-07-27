<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 14/07/17
 * Time: 13:24
 */

namespace EssentialsBundle\Tests\Api;

use EssentialsBundle\Api\ApiProblem;
use EssentialsBundle\Exception\Exceptions;
use PHPUnit\Framework\TestCase;

/**
 * Class ApiProblemTest
 * @package EssentialsBundle\Tests\Api
 */
class ApiProblemTest extends TestCase
{
    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function unknownExceptionCode()
    {
        new ApiProblem(10);
    }

    /**
     * @test
     */
    public function getApiProblemCodeToDefaultExceptionCode()
    {
        $problem = new ApiProblem(0);
        $this->assertEquals(
            Exceptions::RUNTIME_ERROR,
            $problem->getStatusCode()
        );

        $problem = $problem->toArray();

        $this->assertArrayHasKey('type', $problem);
        $this->assertArrayHasKey('title', $problem);
        $this->assertArrayHasKey('status', $problem);
        $this->assertInternalType('string', $problem['type']);
        $this->assertInternalType('string', $problem['title']);
        $this->assertInternalType('int', $problem['status']);
    }

    /**
     * @test
     */
    public function validationProblem()
    {
        $problem = new ApiProblem(Exceptions::VALIDATION_ERROR);
        $this->assertEquals('validation_error', $problem->getType());
    }

    /**
     * @test
     */
    public function apiProblemWithCustomTitle()
    {
        $problem = new ApiProblem(
            Exceptions::VALIDATION_ERROR,
            [
                'title' => 'test'
            ]
        );
        $this->assertEquals('test', $problem->getTitle());
    }
}
