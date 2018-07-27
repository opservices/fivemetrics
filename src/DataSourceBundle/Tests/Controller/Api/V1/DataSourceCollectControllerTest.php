<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 25/09/17
 * Time: 09:56
 */

namespace DataSourceBundle\Tests\Controller\Api\V1;

use DataSourceBundle\Entity\DataSource\DataSource;
use DataSourceBundle\Entity\DataSource\DataSourceCollect;
use EssentialsBundle\Api\Test\ApiTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DataSourceCollectControllerTest
 * @package DataSourceBundle\Tests\Controller\Api\V1
 */
class DataSourceCollectControllerTest extends ApiTestCase
{
    private const DATA_SOURCE_NAME = 'aws.test';

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $em = self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $collectRepo = $em->getRepository(DataSourceCollect::class);
        $dsRepo = $em->getRepository(DataSource::class);

        $ds = $dsRepo->findOneBy([
            'name' => self::DATA_SOURCE_NAME
        ]);

        $collects = $collectRepo->findBy([
            'dataSource' => $ds
        ]);

        foreach ($collects as $collect) {
            $em->remove($collect);
        }

        if (count($collects)) {
            $em->flush();
        }
    }

    /**
     * @test
     */
    public function getCollects()
    {
        $client = $this->login($this->getAccountInstance());
        $client->request('GET', '/web/v1/collect/');

        $collects = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('time', $collects);
        $this->assertArrayHasKey('collects', $collects);

        foreach ($collects['collects'] as $collect) {
            $this->assertArrayHasKey('dataSource', $collect);
            $this->assertArrayHasKey('name', $collect['dataSource']);
            $this->assertArrayNotHasKey('maxConcurrency', $collect['dataSource']);
            $this->assertArrayNotHasKey('collectInterval', $collect['dataSource']);
            $this->assertArrayHasKey('parameters', $collect);

            foreach ($collect['parameters'] as $parameter) {
                $this->assertArrayHasKey('name', $parameter);
                $this->assertArrayHasKey('value', $parameter);
            }

            $this->assertArrayHasKey('isEnabled', $collect);
            $this->assertArrayHasKey('lastUpdate', $collect);
            $this->assertArrayNotHasKey('id', $collect);
        }
    }

    /**
     * @test
     * @dataProvider validCollectsProvider
     */
    public function postCollects($collects)
    {
        $client = $this->login($this->getAccountInstance());
        $client->request('POST', '/web/v1/collect/', [], [], [], $collects);

        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    /**
     * @test
     * @dataProvider validCollectsProvider
     * @depends      postCollects
     */
    public function postDuplicatedCollects($collects)
    {
        $client = $this->login($this->getAccountInstance());
        $client->request('POST', '/web/v1/collect/', [], [], [], $collects);

        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_CONFLICT, $response->getStatusCode());
    }

    /**
     * @test
     * @depends      postDuplicatedCollects
     */
    public function updateCollectParameters()
    {
        $client = $this->login($this->getAccountInstance());
        $client->request('GET', '/web/v1/collect/');

        $content = json_decode($client->getResponse()->getContent(), true);
        $collect = array_values(array_filter($content['collects'], function ($el) {
            return ($el['dataSource']['name'] == self::DATA_SOURCE_NAME);
        }));

        $uid = $collect[0]['uid'];

        $originalMd5 = md5(json_encode($collect['parameters']));
        $collect['parameters'] = [
            [
                'name' => 'aws.key',
                'value' => 'unit.test.key',
            ],
            [
                'name' => 'aws.secret',
                'value' => 'unit.test.secret',
            ],
            [
                'name' => 'aws.region',
                'value' => 'sa-east-1',
            ],
        ];

        $postData = $collect;

        $client->request(
            'PUT',
            '/web/v1/collect/' . $uid,
            [],
            [],
            [],
            json_encode($collect)
        );

        $client->request('GET', '/web/v1/collect/');
        $content = json_decode($client->getResponse()->getContent(), true);

        $collect = array_values(array_filter($content['collects'], function ($el) {
            return ($el['dataSource']['name'] == self::DATA_SOURCE_NAME);
        }));

        $this->assertNotEquals($originalMd5, md5(json_encode($collect)));
        $this->assertEquals(
            md5(json_encode($postData['parameters'])),
            md5(json_encode($collect[0]['parameters']))
        );
    }

    /**
     * @test
     * @depends      postDuplicatedCollects
     */
    public function changeCollectEnabledStatus()
    {
        $client = $this->login($this->getAccountInstance());
        $client->request('GET', '/web/v1/collect/');

        $content = json_decode($client->getResponse()->getContent(), true);
        $collect = array_values(array_filter($content['collects'], function ($el) {
            return ($el['dataSource']['name'] == self::DATA_SOURCE_NAME);
        }));

        $uid = $collect[0]['uid'];
        $isEnabled = $collect[0]['isEnabled'];

        $collect['isEnabled'] = !$isEnabled;

        $client->request(
            'PUT',
            '/web/v1/collect/' . $uid,
            [],
            [],
            [],
            json_encode($collect)
        );

        $client->request('GET', '/web/v1/collect/');
        $content = json_decode($client->getResponse()->getContent(), true);

        $collect = array_values(array_filter($content['collects'], function ($el) {
            return ($el['dataSource']['name'] == self::DATA_SOURCE_NAME);
        }));

        $this->assertNotEquals($isEnabled, $collect[0]['isEnabled']);
    }

    /**
     * @test
     * @depends      postDuplicatedCollects
     */
    public function submitInvalidCollectUpdate()
    {
        $client = $this->login($this->getAccountInstance());
        $client->request('GET', '/web/v1/collect/');

        $content = json_decode($client->getResponse()->getContent(), true);
        $collect = array_values(array_filter($content['collects'], function ($el) {
            return ($el['dataSource']['name'] == self::DATA_SOURCE_NAME);
        }));

        $uid = $collect[0]['uid'];
        $client->request('PUT', '/web/v1/collect/' . $uid);

        $this->assertEquals(
            Response::HTTP_BAD_REQUEST,
            $client->getResponse()->getStatusCode()
        );
    }

    /**
     * @test
     * @depends      postDuplicatedCollects
     */
    public function updateDataSource()
    {
        $client = $this->login($this->getAccountInstance());
        $client->request('GET', '/web/v1/collect/');

        $content = json_decode($client->getResponse()->getContent(), true);
        $collect = array_values(array_filter($content['collects'], function ($el) {
            return ($el['dataSource']['name'] == self::DATA_SOURCE_NAME);
        }));

        $collect = $collect[0];
        $uid = $collect['uid'];
        $updatedCollect = $collect;
        $updatedCollect['dataSource']['name'] = 'aws.ec2';

        $client->request(
            'PUT',
            '/web/v1/collect/' . $uid,
            [],
            [],
            [],
            json_encode($updatedCollect)
        );

        $client->request('GET', '/web/v1/collect/');
        $content = json_decode($client->getResponse()->getContent(), true);

        $updatedCollect = array_values(array_filter($content['collects'], function ($el) {
            return ($el['dataSource']['name'] == 'aws.ec2');
        }));

        $updatedCollect = $updatedCollect[count($updatedCollect) - 1];

        $this->assertNotEmpty($updatedCollect);
        $this->assertEquals($uid, $updatedCollect['uid']);

        $client->request(
            'PUT',
            '/web/v1/collect/' . $uid,
            [],
            [],
            [],
            json_encode($collect)
        );
        $this->assertEquals(
            Response::HTTP_OK,
            $client->getResponse()->getStatusCode()
        );
    }

    /**
     * @test
     * @dataProvider invalidCollectsProvider
     */
    public function postInvalidDataSourceCollects($collects)
    {
        $client = $this->login($this->getAccountInstance());
        $client->request('POST', '/web/v1/collect/', [], [], [], $collects);

        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    /**
     * @test
     * @depends      postDuplicatedCollects
     * @param $collects
     */
    public function deleteCollect()
    {
        $client = $this->login($this->getAccountInstance());
        $client->request('GET', "/web/v1/collect/");

        $response = $client->getResponse();
        $content = json_decode($response->getContent(), true);
        $collect = array_values(array_filter($content['collects'], function ($el) {
            return ($el['dataSource']['name'] == self::DATA_SOURCE_NAME);
        }));

        $uid = $collect[0]['uid'];

        $client->request('DELETE', "/web/v1/collect/" . $uid);
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());

        $client->request('GET', "/web/v1/collect/");
        $content = json_decode($response->getContent(), true);
        $collect = array_values(array_filter($content['collects'], function ($el) {
            return ($el['dataSource']['name'] == self::DATA_SOURCE_NAME);
        }));

        $this->assertEmpty($collect);
    }

    /**
     * @test
     * @depends      deleteCollect
     * @param $collects
     */
    public function deleteNonexistentCollects()
    {
        $client = $this->login($this->getAccountInstance());
        $client->request('DELETE', "/web/v1/collect/asdawdwadsa");

        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    /**
     * @test
     * @dataProvider invalidCollectsProvider
     */
    public function deleteInvalidDataSourceCollects($collects)
    {
        $client = $this->login($this->getAccountInstance());
        $client->request('DELETE', "/web/v1/collect/" . $collects);
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }

    /**
     * @test
     */
    public function getDisabledCollects()
    {
        $client = $this->login($this->getAccountInstance());
        $client->request('GET', '/web/v1/collect/?isEnabled=false');
        $content = json_decode($client->getResponse()->getContent(), true);
        foreach ($content as $collects) {
            foreach ($collects['collects'] as $collect) {
                $this->assertFalse($collect['isEnabled']);
            }
        }
    }

    /**
     * @test
     */
    public function getEnabledCollects()
    {
        $client = $this->login($this->getAccountInstance());
        $client->request('GET', '/web/v1/collect/?isEnabled=true');

        $content = json_decode($client->getResponse()->getContent(), true);
        foreach ($content as $collects) {
            foreach ($collects['collects'] as $collect) {
                $this->assertTrue($collect['isEnabled']);
            }
        }
    }

    /**
     * @test
     */
    public function getOnlyDataSourcesFromCollects()
    {
        $client = $this->login($this->getAccountInstance());
        $client->request('GET', '/web/v1/collect/?properties=["dataSource"]');

        $response = $client->getResponse();
        $content = json_decode($response->getContent(), true);

        foreach ($content['collects'] as $collect) {
            $this->assertCount(1, $collect);
            $this->assertArrayHasKey('dataSource', $collect);
        }
    }

    /**
     * @test
     */
    public function getOnlyUniqueDataSourcesFromCollects()
    {
        $client = $this->login($this->getAccountInstance());
        $client->request('GET', '/web/v1/collect/?properties=["dataSource"]&unique=true');

        $response = $client->getResponse();
        $content = json_decode($response->getContent(), true);

        $ds = [];
        foreach ($content['collects'] as $collect) {
            $this->assertArrayHasKey('dataSource', $collect);
            $this->assertInternalType('array', $collect['dataSource']);
            $this->assertArrayHasKey('name', $collect['dataSource']);

            $this->assertNotContains($collect['dataSource']['name'], $ds);
            $ds[] = $collect['dataSource']['name'];
        }
    }

    public function validCollectsProvider()
    {
        return [
            [
                json_encode(
                    [
                        [
                            "dataSource" => [
                                "name" => self::DATA_SOURCE_NAME,
                            ],
                            "parameters" => [
                                [
                                    "name" => "aws.key",
                                    "value" => "key-test8"
                                ],
                                [
                                    "name" => "aws.secret",
                                    "value" => "secret-test6"
                                ],
                                [
                                    "name" => "aws.region",
                                    "value" => "us-east-1"
                                ],
                            ],
                            "isEnabled" => true,
                            "lastUpdate" => null
                        ]
                    ]
                )
            ]
        ];
    }

    public function invalidCollectsProvider()
    {
        return [
            [
                json_encode(
                    [
                        [
                            "dataSource" => [
                                "name" => "aws.cachopinha"
                            ],
                            "parameters" => [
                                [
                                    "name" => "aws.key",
                                    "value" => "key-test8"
                                ],
                                [
                                    "name" => "aws.secret",
                                    "value" => "secret-test6"
                                ],
                                [
                                    "name" => "aws.region",
                                    "value" => "us-east-1"
                                ],
                            ],
                            "isEnabled" => true,
                            "lastUpdate" => null
                        ]
                    ]
                )
            ]
        ];
    }
}
