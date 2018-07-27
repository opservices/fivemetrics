<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 09/04/18
 * Time: 09:46
 */

namespace EssentialsBundle\Tests\Controller\Api\V1;

use EssentialsBundle\Api\Test\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;

class UnauthenticatedRequestRedirectTest extends ApiTestCase
{
    /**
     * @test
     **/
    public function getAccountActionForbiddenApi()
    {
        $client = $this->getClient();

        $client->request('GET', '/api/v1/account/');
        $response = $client->getResponse();

        $this->assertEquals(
            Response::HTTP_FORBIDDEN,
            $response->getStatusCode()
        );

        $this->assertTrue($response->headers->contains(
            'Content-Type',
            'application/problem+json'
        ));
    }

    /**
     * @test
     **/
    public function getAccountActionForbiddenWeb()
    {
        $client = $this->getClient();

        $client->request('GET', '/web/v1/account/');
        $response = $client->getResponse();

        $this->assertEquals(
            Response::HTTP_FOUND,
            $response->getStatusCode()
        );

        $this->assertTrue($response->headers->contains('location', '/login'));
        $this->assertTrue($response->headers->contains(
            'Content-Type',
            'application/json'
        ));
    }

    /**
     * @test
     **/
    public function getAccountActionForbiddenWebXhr()
    {
        $client = $this->getClient();
        $headers = [ 'HTTP_X-Requested-With' => 'XMLHttpRequest' ];

        $client->request('GET', '/web/v1/account/', [], [], $headers);
        $response = $client->getResponse();

        $this->assertEquals(
            Response::HTTP_FORBIDDEN,
            $response->getStatusCode()
        );

        $this->assertTrue($response->headers->contains(
            'Content-Type',
            'application/problem+json'
        ));
    }
}
