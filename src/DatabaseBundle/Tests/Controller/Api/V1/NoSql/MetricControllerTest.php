<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 04/07/17
 * Time: 09:38
 */

namespace DatabaseBundle\Tests\Controller\Api\V1\NoSql;

use EssentialsBundle\Api\Test\ApiTestCase;
use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Entity\Builder\EntityBuilderProvider;
use Symfony\Component\HttpFoundation\Response;

class MetricControllerTest extends ApiTestCase
{
    /**
     * @test
     */
    public function listMetricsAction()
    {
        $client = $this->login($this->getAccountInstance());
        $client->request('GET', '/web/v1/metrics/');

        $metrics = json_decode($client->getResponse()->getContent(), true);

        $this->assertNotEmpty($metrics);

        foreach ($metrics as $metric) {
            $this->assertArrayHasKey('name', $metric);
            $this->assertArrayHasKey('tags', $metric);
            $this->assertInternalType('string', $metric['name']);
            $this->assertInternalType('array', $metric['tags']);
        }
    }

    /**
     * @test
     */
    public function tryAccessApiWithInvalidUser()
    {
        $account = EntityBuilderProvider::factory(Account::class)
            ->factory([
                'id' => 1,
                'email' => 'fake@fake.com',
                'roles' => [ 'ROLE_API_V1' ],
                'uid' => 'fake',
                'password' => '$2y$13$g3hZXfnm/j4ZebUodyHgjekurf5cju7L0UmtjeWwideOqKNKECgY6',
                'username' => 'fake',
            ]);

        $client = $this->login($account);
        $client->request('GET', '/web/v1/metrics/');

        $this->assertTrue($client->getResponse()->isRedirect('/login'));
    }

    /**
     * @test
     */
    public function getMetricAction()
    {
        // Defined in NoSql fixtures
        $metricName = 'test.test';

        $client = $this->login($this->getAccountInstance());
        $client->request('GET', '/web/v1/metrics/' . $metricName);

        $metric = json_decode($client->getResponse()->getContent(), true);

        $this->assertNotEmpty($metric);
        $this->assertArrayHasKey('name', $metric);
        $this->assertArrayHasKey('tags', $metric);
        $this->assertInternalType('string', $metric['name']);
        $this->assertInternalType('array', $metric['tags']);

        $this->assertEquals($metricName, $metric['name']);
    }

    /**
     * @test
     */
    public function getUndefinedMetricAction()
    {
        $metricName = 'fake.fake';

        $client = $this->login($this->getAccountInstance());
        $client->request('GET', '/web/v1/metrics/' . $metricName);

        $metric = json_decode($client->getResponse()->getContent(), true);

        $this->assertEmpty($metric);
        $this->assertInternalType('array', $metric);

        $this->assertEquals(
            Response::HTTP_NOT_FOUND,
            $client->getResponse()->getStatusCode()
        );
    }

    /**
     * @test
     * @dataProvider getMetricHistoryQueries
     */
    public function getMetricHistoryAction(string $query)
    {
        $metricName = 'test.test';

        $uri = sprintf(
            '/web/v1/metrics/%s/history?q=%s',
            $metricName,
            $query
        );

        $client = $this->login($this->getAccountInstance());
        $client->request('GET', $uri);

        $series = json_decode($client->getResponse()->getContent(), true);

        $this->assertNotEmpty($series);
        $this->assertArrayHasKey('series', $series);
        $this->assertArrayHasKey('period', $series);
        $this->assertInternalType('array', $series['series']);
        $this->assertInternalType('string', $series['period']);

        $this->assertNotEmpty($series['series']);
        $this->assertArrayHasKey('name', $series['series'][0]);
        $this->assertArrayHasKey('tags', $series['series'][0]);
        $this->assertArrayHasKey('points', $series['series'][0]);
        $this->assertArrayHasKey('minimum', $series['series'][0]);
        $this->assertArrayHasKey('maximum', $series['series'][0]);
        $this->assertInternalType('string', $series['series'][0]['name']);
        $this->assertInternalType('array', $series['series'][0]['tags']);
        $this->assertInternalType('array', $series['series'][0]['points']);
        $this->assertInternalType('int', $series['series'][0]['minimum']);
        $this->assertInternalType('int', $series['series'][0]['maximum']);
    }

    public function getMetricHistoryQueries()
    {
        return [
            [ '{"periods":["thisyear"],"query":{"limit":1}}' ],
            [ '{"periods":["thisyear"],"query":{"columns":["value","minimum"],"limit":1}}' ],
            [ '{"periods":["thisyear"],"query":{"query":{},"limit":1}}' ],
        ];
    }

    /**
     * @test
     */
    public function sendRequestWithInvalidQuery()
    {
        $metricName = 'test.test';

        $uri = sprintf(
            '/web/v1/metrics/%s/history?q=%s',
            $metricName,
            '{"query":{"groupBy":{}}}'
        );

        $client = $this->login($this->getAccountInstance());
        $client->request('GET', $uri);

        $error = json_decode($client->getResponse()->getContent(), true);

        $this->assertNotEmpty($error);
        $this->assertArrayHasKey('type', $error);
        $this->assertArrayHasKey('title', $error);
        $this->assertArrayHasKey('status', $error);
        $this->assertArrayHasKey('detail', $error);
        $this->assertInternalType('string', $error['type']);
        $this->assertInternalType('string', $error['title']);
        $this->assertInternalType('int', $error['status']);
        $this->assertInternalType('string', $error['detail']);
    }
}
