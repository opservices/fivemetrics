<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 14/07/17
 * Time: 13:19
 */

namespace EssentialsBundle\Tests\Api;

use EssentialsBundle\Api\ApiProblem;
use EssentialsBundle\Api\ApiProblemException;
use EssentialsBundle\Exception\Exceptions;
use PHPUnit\Framework\TestCase;

/**
 * Class ApiProblemExceptionTest
 * @package EssentialsBundle\Tests\Api
 */
class ApiProblemExceptionTest extends TestCase
{
    /**
     * @test
     */
    public function getApiProblem()
    {
        $apiProblem = new ApiProblem(
            Exceptions::RUNTIME_ERROR,
            [ 'test' ]
        );

        $exception = new ApiProblemException($apiProblem);

        $this->assertSame($apiProblem, $exception->getApiProblem());
    }
}
