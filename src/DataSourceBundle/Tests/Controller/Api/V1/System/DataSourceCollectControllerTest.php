<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 25/09/17
 * Time: 09:56
 */

namespace DataSourceBundle\Tests\Controller\Api\V1\System;

use EssentialsBundle\Api\Test\ApiTestCase;

class DataSourceCollectControllerTest extends ApiTestCase
{
    /**
     * @test
     */
    public function getCollects()
    {
        $client = $this->login($this->getAccountInstance());
        $client->request('GET', '/web/v1/system/collect/');

        $content = json_decode($client->getResponse()->getContent(), true);

        foreach ($content as $collects) {
            $this->assertArrayHasKey('time', $collects);
            $this->assertArrayHasKey('account', $collects);
            $this->assertArrayHasKey('collects', $collects);

            foreach ($collects['collects'] as $collect) {
                $this->assertArrayHasKey('dataSource', $collect);
                $this->assertArrayHasKey('name', $collect['dataSource']);
                $this->assertArrayHasKey('maxConcurrency', $collect['dataSource']);
                $this->assertArrayHasKey('collectInterval', $collect['dataSource']);
                $this->assertArrayHasKey('parameters', $collect);

                foreach ($collect['parameters'] as $parameter) {
                    $this->assertArrayHasKey('name', $parameter);
                    $this->assertArrayHasKey('value', $parameter);
                }

                $this->assertArrayHasKey('isEnabled', $collect);
                $this->assertArrayHasKey('lastUpdate', $collect);
                $this->assertArrayHasKey('id', $collect);
            }
        }
    }

    /**
     * @test
     */
    public function getDisabledCollects()
    {
        $client = $this->login($this->getAccountInstance());
        $client->request('GET', '/web/v1/system/collect/?isEnabled=false');

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
        $client->request('GET', '/web/v1/system/collect/?isEnabled=true');

        $content = json_decode($client->getResponse()->getContent(), true);
        foreach ($content as $collects) {
            foreach ($collects['collects'] as $collect) {
                $this->assertTrue($collect['isEnabled']);
            }
        }
    }
}
