<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 04/07/17
 * Time: 09:38
 */

namespace DatabaseBundle\Tests\Controller\Api\V1\NoSql;

use EssentialsBundle\Api\Test\ApiTestCase;

class TagControllerTest extends ApiTestCase
{
    /**
     * @test
     * @dataProvider getMetricHistoryQueries
     */
    public function getMetricHistoryAction(string $query)
    {
        $client = $this->login($this->getAccountInstance());
        $client->request('GET', '/web/v1/tags/metrics/?q=' . $query);

        $metrics = json_decode($client->getResponse()->getContent(), true);

        foreach ($metrics as $metric) {
            $this->assertNotEmpty($metric);
            $this->assertArrayHasKey('name', $metric);
            $this->assertInternalType('string', $metric['name']);
            $this->assertNotEmpty($metric['name']);
            $this->assertArrayHasKey('tags', $metric);
            $this->assertInternalType('array', $metric['tags']);
            $this->assertArrayHasKey('system', $metric['tags']);
            $this->assertArrayHasKey('custom', $metric['tags']);
            $this->assertInternalType('array', $metric['tags']['system']);
            $this->assertInternalType('array', $metric['tags']['custom']);

            $tags = $metric['tags'];
            foreach ($tags['system'] as $tag) {
                $this->assertArrayHasKey('name', $tag);
                $this->assertInternalType('string', $tag['name']);
                $this->assertNotEmpty($tag['name']);

                $this->assertArrayHasKey('values', $tag);
                $this->assertInternalType('array', $tag['values']);
                $this->assertNotEmpty($tag['values']);
            }

            foreach ($tags['custom'] as $tag) {
                $this->assertArrayHasKey('name', $tag);
                $this->assertInternalType('string', $tag['name']);
                $this->assertNotEmpty($tag['name']);

                $this->assertArrayHasKey('values', $tag);
                $this->assertInternalType('array', $tag['values']);
                $this->assertNotEmpty($tag['values']);
            }
        }
    }

    public function getMetricHistoryQueries()
    {
        return [
            [ '{"metrics":["aws.ec2.instances","aws.ec2.ebs","junk"],"type":"all"}' ],
            [ '{"metrics":["aws.ec2.instances","aws.ec2.ebs","junk"],"type":"system"}' ],
            [ '{"metrics":["aws.ec2.instances","aws.ec2.ebs","junk"],"type":"custom"}' ],
            [ '' ],
        ];
    }
}
