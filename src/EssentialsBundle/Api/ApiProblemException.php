<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 18/05/17
 * Time: 15:45
 */

namespace EssentialsBundle\Api;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiProblemException extends HttpException
{
    private $apiProblem;

    public function __construct(
        ApiProblem $apiProblem,
        \Exception $previous = null,
        array $headers = [],
        $code = 0
    ) {
        $this->apiProblem = $apiProblem;
        $statusCode = $apiProblem->getStatusCode();
        $message = $apiProblem->getTitle();
        parent::__construct($statusCode, $message, $previous, $headers, $code);
    }

    public function getApiProblem()
    {
        return $this->apiProblem;
    }
}
