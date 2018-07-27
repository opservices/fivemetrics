<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 24/11/17
 * Time: 13:54
 */

namespace CollectorBundle\Tests\Controller;

use CollectorBundle\Collect\CollectBucket;
use CollectorBundle\Collect\CollectCollection;
use CollectorBundle\Collect\DataSource;
use CollectorBundle\Collect\Discovery\Collect;
use CollectorBundle\Collect\ParameterCollection;
use CollectorBundle\Controller\Api\V1\DiscoveryController;
use Doctrine\Common\Cache\PredisCache;
use EssentialsBundle\Api\Test\ApiTestCase;
use EssentialsBundle\Cache\CacheFactory;
use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Entity\DateTime\DateTime;
use GearmanBundle\Job\Status;
use GearmanBundle\TaskManager\TaskManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DiscoveryControllerTest extends ApiTestCase
{
    protected $validDiscoveryId = null;

    /**
     * @test
     */
    public function getUnknownDiscoveryStatus()
    {
        $id = 'fakeId';
        $client = $this->logIn($this->getAccountInstance());
        $client->request('GET', '/web/v1/discovery/' . $id);

        $response = $client->getResponse();
        $content = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('id', $content);
        $this->assertEquals($content['id'], $id);
        $this->assertArrayHasKey('status', $content);
        $this->assertEquals($content['status'], 'unknown');

        $this->assertEquals(
            Response::HTTP_OK,
            $response->getStatusCode()
        );
    }

    /**
     * @test
     */
    public function startInvalidDiscovery()
    {
        $client = $this->logIn($this->getAccountInstance());
        $client->request('POST', '/web/v1/discovery/');

        $response = $client->getResponse();
        $this->assertEquals(
            Response::HTTP_BAD_REQUEST,
            $response->getStatusCode()
        );
    }

    /**
     * @test
     */
    public function startDiscovery()
    {
        $container = $this->getContainer();
        $controller = $container->get(DiscoveryController::class);
        $controller->loginUser($this->getAccountInstance(), new Request());
        $controller->setTaskManager($this->getMockedTaskManager(['runBackground' => 'gearman-id']));

        $content = '[
                {
                    "dataSource": {
                        "name": "aws.ec2"
                    },
                    "parameters": [
                        {
                            "name": "aws.key",
                            "value": "aws.key.test"
                        },
                        {
                            "name": "aws.secret",
                            "value": "aws.secret.test"
                        },
                        {
                            "name": "aws.region",
                            "value": "us-east-1"
                        }
                    ]
                }
            ]';

        $response = $controller->startDiscoveryAction(new Request(
            [],
            [],
            [],
            [],
            [],
            [],
            $content
        ));

        $responseContent = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('id', $responseContent);
        $this->assertStringStartsWith('discovery-', $responseContent['id']);
        $this->assertArrayHasKey('status', $responseContent);
        $this->assertEquals('queued', $responseContent['status']);

        $this->assertEquals(
            Response::HTTP_CREATED,
            $response->getStatusCode()
        );

        return $responseContent['id'];
    }

    /**
     * @test
     * @dataProvider discoveryStatusDataProvider
     */
    public function discoveryStatusAction(CollectBucket $bucket)
    {
        $container = $this->getContainer();

        /** @var DiscoveryController $controller */
        $controller = $container->get(DiscoveryController::class);
        $controller->loginUser($this->getAccountInstance(), new Request());
        $controller->setCacheFactory($this->getMockedCacheFactory(['fetch' => $bucket]));
        $controller->setTaskManager($this->getMockedTaskManager(['getJobStatus' => new Status([])]));

        $id = 'execution-a4eda02b9817ee83e7518210400ab342';
        $response = $controller->discoveryStatusAction($id, true);
        $response = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('id', $response);
        $this->assertEquals($id, $response['id']);
        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('finished', $response['status']);
        $this->assertArrayHasKey('result', $response);
        $this->assertInternalType('array', $response['result']);
        foreach ($response['result'] as $result) {
            $this->assertArrayHasKey('dataSource', $result);
            $this->assertArrayHasKey('name', $result['dataSource']);
            $this->assertInternalType('string', $result['dataSource']['name']);
            $this->assertArrayHasKey('parameters', $result);
            $this->assertArrayHasKey('metrics', $result);
            $this->assertArrayHasKey('errors', $result);
            $this->assertInternalType('array', $result['errors']);

            foreach ($result['errors'] as $error) {
                $this->assertInternalType('string', $error);
            }
        }
    }

    /**
     * @return array
     */
    public function discoveryStatusDataProvider()
    {
        $ds = new DataSource('aws.ec2', 1, 300);
        $collect = new Collect($ds, new ParameterCollection());
        $collectWithError = new Collect($ds, new ParameterCollection());
        $collectWithError->addError('AWS was not able to validate the provided access credentials');

        $account = new Account();
        $account->setUsername('tester');

        $dateTime = new DateTime();
        return [
            [
                new CollectBucket($account, $dateTime, new CollectCollection([$collectWithError]))
            ],
            [
                new CollectBucket($account, $dateTime, new CollectCollection([$collect]))
            ],
        ];
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
}
