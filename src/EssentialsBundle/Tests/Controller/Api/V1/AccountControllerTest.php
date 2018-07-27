<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 30/08/17
 * Time: 08:27
 */

namespace EssentialsBundle\Tests\Controller\Api\V1;

use EssentialsBundle\Api\Test\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;

class AccountControllerTest extends ApiTestCase
{
    /**
     * @test
     */
    public function getAccount()
    {
        $client = $this->login($this->getAccountInstance());

        $client->request('GET', '/web/v1/account/');
        $content = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('uid', $content);
        $this->assertArrayHasKey('username', $content);
        $this->assertArrayHasKey('paymentType', $content);

        $this->assertEquals(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode()
        );

        $this->assertEquals(
            'application/json',
            $client->getResponse()->headers->get('Content-Type')
        );
    }

    /**
     * @test
     */
    public function getAccountWithoutBeLogged()
    {
        $client = $this->getClient();

        $client->request('GET', '/web/v1/account/');

        $this->assertEquals(
            Response::HTTP_FOUND,
            $client->getResponse()->getStatusCode()
        );
    }

    /**
     * @test
     */
    public function registerActionWithNotAllowedUser()
    {
        $account = $this->getAccountInstance();

        $roles = $account->getRoles();
        $roles = array_filter($roles, function ($role) {
            return ($role != 'ROLE_SYSTEM');
        });

        $account->setRoles($roles);

        $client = $this->login($account);
        $client->request('POST', '/web/v1/account/');

        $this->validateErrorResponse(
            $client->getResponse(),
            Response::HTTP_FORBIDDEN
        );
    }

    /*
     * The API account creation is blocked by now. So this test doesn't make any sense.
     */
    public function registerActionWithoutEmail()
    {
        $client = $this->getClient();
        $client->request(
            'POST',
            '/web/v1/account/',
            [],
            [],
            [],
            '{"username":"test"}'
        );

        $this->validateErrorResponse(
            $client->getResponse(),
            Response::HTTP_BAD_REQUEST
        );
    }

    /*
     * The API account creation is blocked by now. So this test doesn't make any sense.
     */
    public function registerActionWithDuplicatedEmail()
    {
        $client = $this->getClient();
        $client->request(
            'POST',
            '/web/v1/account/',
            [],
            [],
            [],
            '{"email":"tester@fivemetrics.io","username":"test"}'
        );

        $this->validateErrorResponse(
            $client->getResponse(),
            Response::HTTP_BAD_REQUEST
        );
    }

    /**
     * @param Response $response
     * @param int $statusCode
     */
    protected function validateErrorResponse(
        Response $response,
        int $statusCode
    ) {
        $content = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('type', $content);
        $this->assertArrayHasKey('title', $content);
        $this->assertArrayHasKey('status', $content);
        $this->assertArrayHasKey('detail', $content);

        $this->assertEquals(
            $statusCode,
            $response->getStatusCode()
        );

        $this->assertEquals(
            'application/problem+json',
            $response->headers->get('Content-Type')
        );
    }

    /**
     * @test
     * The API account creation is blocked by now. So this test doesn't make any sense.
     */
    public function registerAction()
    {
        $client = $this->logIn($this->getAccountInstance());
        $client->request(
            'POST',
            '/web/v1/account/',
            [],
            [],
            [],
            '{"email":"unit@fivemetrics.io","username":"test"}'
        );

        $response = $client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('username', $content);
        $this->assertArrayHasKey('uid', $content);

        $this->assertEquals(
            Response::HTTP_CREATED,
            $response->getStatusCode()
        );

        $this->assertEquals(
            'application/json',
            $response->headers->get('Content-Type')
        );
    }

    /**
     * @test
     * @depends registerAction
     */
    public function deleteAction()
    {
        $client = $this->login($this->getAccountInstance([
            'email' => 'developer@fivemetrics.io'
        ]));

        $user = $this->getAccountInstance([
            'email' => 'unit@fivemetrics.io'
        ]);

        $client->request('DELETE', '/web/v1/account/' . $user->getUid());
        $content = json_decode($client->getResponse()->getContent(), true);

        $this->assertEmpty($content);

        $this->assertEquals(
            Response::HTTP_NO_CONTENT,
            $client->getResponse()->getStatusCode()
        );

        $this->assertEquals(
            null,
            $client->getResponse()->headers->get('Content-Type')
        );
    }

    /**
     * @test
     */
    public function deleteActionWithInvalidAccount()
    {
        $client = $this->login($this->getAccountInstance([
            'email' => 'developer@fivemetrics.io'
        ]));

        $client->request('DELETE', '/web/v1/account/fake@junk.com');

        $this->validateErrorResponse(
            $client->getResponse(),
            Response::HTTP_NOT_FOUND
        );
    }

    /**
     * @test
     */
    public function deleteActionWithUnauthorizedAccount()
    {
        $account = $this->getAccountInstance([
            'email' => 'tester@fivemetrics.io'
        ]);

        $roles = $account->getRoles();
        $roles = array_filter($roles, function ($role) {
            return (! in_array($role, [ 'ROLE_ADMIN', 'ROLE_SYSTEM', 'ROLE_DEVEL', ]));
        });
        $account->setRoles($roles);

        $client = $this->login($account);

        $client->request('DELETE', '/web/v1/account/fake@junk.com');

        $this->validateErrorResponse(
            $client->getResponse(),
            Response::HTTP_FORBIDDEN
        );
    }

    /**
     * @test
     */
    public function generateToken()
    {
        $client = $this->login($this->getAccountInstance([
            'email' => 'developer@fivemetrics.io'
        ]));

        $client->request('PUT', '/web/v1/account/token/');

        $content = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('uid', $content);
        $this->assertArrayHasKey('apiKey', $content);
    }

    /**
     * @test
     */
    public function eraseToken()
    {
        $client = $this->login($this->getAccountInstance([
            'email' => 'developer@fivemetrics.io'
        ]));

        $client->request('DELETE', '/web/v1/account/token/');

        $content = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('uid', $content);
    }
}
