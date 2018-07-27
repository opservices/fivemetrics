<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 30/08/17
 * Time: 08:27
 */

namespace EssentialsBundle\Tests\Controller\Api\V1;

use EssentialsBundle\Api\Test\ApiTestCase;
use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Entity\Account\AccountConfiguration;
use EssentialsBundle\Exception\Exceptions;
use Symfony\Component\HttpFoundation\Response;

class AccountConfigurationControllerTest extends ApiTestCase
{

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        $em = self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $confRepository = $em->getRepository(AccountConfiguration::class);
        $accountRepository = $em->getRepository(Account::class);

        $account = $accountRepository->findOneBy([ 'email' => 'tester@fivemetrics.io' ]);
        $confs   = $confRepository->findBy([ 'account' => $account ]);

        foreach ($confs as $conf) {
            $em->remove($conf);
        }

        $em->flush();
    }

    /**
     * @test
     */
    public function getConfigurationsAction()
    {
        $client = $this->login($this->getAccountInstance());

        $client->request('GET', '/web/v1/account/configuration/');
        $content = json_decode($client->getResponse()->getContent(), true);

        $this->assertEmpty($content);

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
    public function postInvalidConfigurationNameAction()
    {
        $client = $this->login($this->getAccountInstance());

        $client->request(
            'POST',
            '/web/v1/account/configuration/',
            [],
            [],
            [],
            '[{"name":"a a","value":"bbb"}]'
        );
        $this->validateErrorResponse($client->getResponse(), Response::HTTP_BAD_REQUEST);
    }

    /**
     * @test
     */
    public function postConfigurationsAction()
    {
        $client = $this->login($this->getAccountInstance());

        $client->request(
            'POST',
            '/web/v1/account/configuration/',
            [],
            [],
            [],
            '[{"name":"aaa","value":"bbb"}]'
        );
        $content = json_decode($client->getResponse()->getContent(), true);

        $this->assertNotEmpty($content);
        $this->assertArrayHasKey('name', $content[0]);
        $this->assertArrayHasKey('value', $content[0]);
        $this->assertEquals('aaa', $content[0]['name']);
        $this->assertEquals('bbb', $content[0]['value']);

        $this->assertEquals(
            Response::HTTP_CREATED,
            $client->getResponse()->getStatusCode()
        );

        $this->assertEquals(
            'application/json',
            $client->getResponse()->headers->get('Content-Type')
        );
    }

    /**
     * @test
     * @depends postConfigurationsAction
     */
    public function putConfigurationsAction()
    {
        $client = $this->login($this->getAccountInstance());

        $client->request('GET', '/web/v1/account/configuration/');
        $content = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode()
        );

        $name = $content[0]['name'];
        $client->request(
            'PUT',
            '/web/v1/account/configuration/' . $name,
            [],
            [],
            [],
            '{"name":"bbb","value":"ccc"}'
        );
        $content = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode()
        );

        $this->assertEquals(
            [
                'name' => $content['name'],
                'value' => $content['value']
            ],
            $content
        );
    }

    /**
     * @test
     * @depends postConfigurationsAction
     */
    public function deleteConfigurationAction()
    {
        $client = $this->login($this->getAccountInstance());

        $client->request('GET', '/web/v1/account/configuration/');
        $content = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode()
        );

        $name = $content[0]['name'];

        $client->request('DELETE', '/web/v1/account/configuration/' . $name);
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
    public function postDuplicatedConfiguration()
    {
        $client   = $this->login($this->getAccountInstance());

        $client->request(
            'POST',
            '/web/v1/account/configuration/',
            [],
            [],
            [],
            '[{"name":"a","value":"b"},{"name":"a","value":"c"}]'
        );

        $this->validateErrorResponse(
            $client->getResponse(),
            Exceptions::CONFLICT
        );
    }

    /**
     * @test
     */
    public function postSameConfigurationTwice()
    {
        $client   = $this->login($this->getAccountInstance());
        $postData = '[{"name":"aaa","value":"bbb"}]';

        $client->request(
            'POST',
            '/web/v1/account/configuration/',
            [],
            [],
            [],
            $postData
        );
        $client->request(
            'POST',
            '/web/v1/account/configuration/',
            [],
            [],
            [],
            $postData
        );

        $this->validateErrorResponse(
            $client->getResponse(),
            Exceptions::CONFLICT
        );
    }

    /**
     * @test
     */
    public function deleteInvalidConfiguration()
    {
        $client   = $this->login($this->getAccountInstance());

        $client->request('DELETE', '/web/v1/account/configuration/0');

        $this->validateErrorResponse(
            $client->getResponse(),
            Exceptions::RESOURCE_NOT_FOUND
        );
    }

    /**
     * @test
     */
    public function updateInvalidConfiguration()
    {
        $client   = $this->login($this->getAccountInstance());
        $client->request(
            'PUT',
            '/web/v1/account/configuration/0',
            [],
            [],
            [],
            '{"name":"aaa","value":"bbb"}'
        );

        $this->validateErrorResponse(
            $client->getResponse(),
            Exceptions::RESOURCE_NOT_FOUND
        );
    }

    /**
     * @test
     */
    public function putInvalidConfiguration()
    {
        $client   = $this->login($this->getAccountInstance());
        $client->request(
            'PUT',
            '/web/v1/account/configuration/aaa',
            [],
            [],
            [],
            '{"name":"aa a","value":"bbb"}'
        );

        $this->validateErrorResponse(
            $client->getResponse(),
            Exceptions::VALIDATION_ERROR
        );
    }

    /**
     * @test
     */
    public function getConfigurationsActionWithoutBeLogged()
    {
        $client = $this->getClient();
        $client->request('GET', '/web/v1/account/configuration/');

        // Redirect response code
        $this->assertEquals(
            Response::HTTP_FOUND,
            $client->getResponse()->getStatusCode()
        );
    }

    /**
     * @test
     */
    public function postConfigurationActionWithoutBeLogged()
    {
        $client = $this->getClient();
        $client->request('POST', '/web/v1/account/configuration/');

        // Redirect response code
        $this->assertEquals(
            Response::HTTP_FOUND,
            $client->getResponse()->getStatusCode()
        );
    }

    /**
     * @test
     */
    public function putConfigurationActionWithoutBeLogged()
    {
        $client = $this->getClient();
        $client->request('PUT', '/web/v1/account/configuration/0');

        // Redirect response code
        $this->assertEquals(
            Response::HTTP_FOUND,
            $client->getResponse()->getStatusCode()
        );
    }

    /**
     * @test
     */
    public function deleteConfigurationActionWithoutBeLogged()
    {
        $client = $this->getClient();
        $client->request('DELETE', '/web/v1/account/configuration/0');

        // Redirect response code
        $this->assertEquals(
            Response::HTTP_FOUND,
            $client->getResponse()->getStatusCode()
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
}
