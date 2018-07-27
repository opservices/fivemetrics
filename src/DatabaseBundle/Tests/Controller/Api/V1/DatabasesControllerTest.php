<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 17/05/17
 * Time: 12:00
 */

namespace DatabaseBundle\Tests\Controller\Api\V1;

use EssentialsBundle\Api\Test\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DatabaseControllerTest
 * @package DatabaseBundle\Tests\Controller\Api\V1
 */
//class DatabasesControllerTest extends ApiTestCase
class DatabasesControllerTest
{
    /**
     * @test
     */
    public function createDatabaseAction()
    {
        $response = $this->client->post('/api/database/', [
            'json' => [
                'id' => 'test'
            ]
        ]);

        $this->assertEquals(
            Response::HTTP_CREATED,
            $response->getStatusCode()
        );

        $this->assertEquals(
            [ '/api/database/test' ],
            $response->getHeader('Location')
        );

        $this->asserter()->assertResponsePropertiesExist(
            $response,
            [ 'id' ]
        );

        $this->asserter()->assertResponsePropertyEquals(
            $response,
            'id',
            'test'
        );
    }

    /**
     * @test
     */
    public function deleteDatabaseAction()
    {
        $response = $this->client->delete('/api/database/test');

        $this->assertEquals(
            Response::HTTP_NO_CONTENT,
            $response->getStatusCode()
        );
    }

    public function createInvalidDatabase()
    {
        $response = $this->client->post('/api/database/', [
            'json' => [
                'id' => 'test'
            ]
        ]);

        $this->assertEquals(
            Response::HTTP_BAD_REQUEST,
            $response->getStatusCode()
        );
    }
}
