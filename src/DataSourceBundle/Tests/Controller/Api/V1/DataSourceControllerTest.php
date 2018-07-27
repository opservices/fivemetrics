<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 25/09/17
 * Time: 10:32
 */

namespace DataSourceBundle\Tests\Controller\Api\V1;


use DataSourceBundle\Controller\Api\V1\DataSourceController;
use Doctrine\Common\Cache\PredisCache;
use EssentialsBundle\Api\Test\ApiTestCase;
use EssentialsBundle\Cache\CacheFactory;
use GearmanBundle\TaskManager\TaskManager;
use Symfony\Component\HttpFoundation\Request;

class DataSourceControllerTest extends ApiTestCase
{
    /**
     * @test
     */
    public function getDataSources()
    {
        $client = $this->login($this->getAccountInstance());
        $client->request('GET', '/web/v1/datasource/');

        $content = json_decode($client->getResponse()->getContent(), true);

        foreach ($content as $ds) {
            $this->assertArrayHasKey('name', $ds);
            $this->assertArrayHasKey('label', $ds);
            $this->assertInternalType('string', $ds['label']);
            $this->assertNotEmpty($ds['label']);
            $this->assertArrayHasKey('description', $ds);
            $this->assertInternalType('string', $ds['description']);
            $this->assertNotEmpty($ds['description']);
            $this->assertArrayHasKey('icon', $ds);
            $this->assertInternalType('string', $ds['icon']);
            $this->assertNotEmpty($ds['icon']);

            $this->assertArrayHasKey('parameters', $ds);
            $this->assertInternalType('array', $ds['parameters']);
            foreach ($ds['parameters'] as $parameter) {
                $this->assertArrayHasKey('name', $parameter);
                $this->assertInternalType('string', $parameter['name']);
                $this->assertNotEmpty($parameter['name']);
                $this->assertArrayHasKey('label', $parameter);
                $this->assertInternalType('string', $parameter['label']);
                $this->assertNotEmpty($parameter['label']);
                $this->assertArrayHasKey('description', $parameter);
                $this->assertInternalType('string', $parameter['description']);
                $this->assertNotEmpty($parameter['description']);
            }

            $this->assertArrayHasKey('groups', $ds);
            $this->assertInternalType('array', $ds['groups']);
            foreach ($ds['groups'] as $group) {
                $this->assertArrayHasKey('name', $group);
                $this->assertInternalType('string', $group['name']);
                $this->assertNotEmpty($group['name']);
            }
        }
    }

    /**
     * @test
     * @dataProvider executeDataSourceActionDataProvider
     * @param $content
     */
    public function executeDataSourceAction($content)
    {
        $request = new Request([], [], [], [], [], [], $content);
        $container = $this->getContainer();

        /** @var DataSourceController $controller */
        $controller = $container->get(DataSourceController::class);
        $controller->loginUser($this->getAccountInstance(), new Request());

        $controller->setCacheFactory($this->getMockedCacheFactory(['save' => true]));
        $controller->setTaskManager($this->getMockedTaskManager(['runBackground' => true]));
        $response = $controller->executeDataSourceAction($request);
        $data = json_decode($response->getContent(), true);

        $this->assertInternalType('array', $data);

        $this->assertArrayHasKey('id', $data);
        $this->assertInternalType('string', $data['id']);
        $this->assertNotEmpty($data['id']);

        $this->assertArrayHasKey('status', $data);
        $this->assertInternalType('string', $data['status']);
        $this->assertNotEmpty($data['status']);

        $this->assertEquals(201, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function executeDataSourceActionInvalidRequest()
    {
        $client = $this->logIn($this->getAccountInstance());

        $client->request(
            'POST',
            '/web/v1/datasource/execute/'
        );

        $response = $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function executeInvalidDataSource()
    {
        $client = $this->logIn($this->getAccountInstance());

        $client->request(
            'POST',
            '/web/v1/datasource/execute/',
            [],
            [],
            [],
            '[
                    {
                        "dataSource": {
                            "name": "test.unit"
                        }
                    }
                ]'
        );

        $response = $client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function getMockedCacheFactory(array $data)
    {
        $cache = $this->getMockBuilder(PredisCache::class)
            ->disableOriginalConstructor()
            ->setMethods(array_keys($data))
            ->getMock();

        foreach ($data as $method => $return) {
            $cache->expects($this->once())
                ->method($method)
                ->willReturn($return);
        }

        $cacheFactory = $this->getMockBuilder(CacheFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['factory'])
            ->getMock();

        $cacheFactory->expects($this->once())
            ->method('factory')
            ->willReturn($cache);

        return $cacheFactory;
    }

    public function getMockedTaskManager(array $methods)
    {
        $tm = $this->getMockBuilder(TaskManager::class)
            ->disableOriginalConstructor()
            ->setMethods(array_keys($methods))
            ->getMock();

        foreach ($methods as $method => $return) {
            $tm->expects($this->once())
                ->method($method)
                ->willReturn($return);
        }

        return $tm;
    }

    public function executeDataSourceActionDataProvider()
    {
        return [
            [
                '[
                    {
                        "dataSource": {
                            "name": "aws.ec2"
                        },
                        "parameters": [
                            {
                                "name": "aws.key",
                                "value": "test-key"
                            },
                            {
                                "name": "aws.secret",
                                "value": "test-secret"
                            },
                            {
                                "name": "aws.region",
                                "value": "sa-east-1"
                            }
                        ]
                    }
                ]'
            ]
        ];
    }
}
