<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 05/06/17
 * Time: 15:48
 */

namespace DatabaseBundle\Tests\Controller\Api\V1\Middleware\NoSql\QueryProcessor;

use DatabaseBundle\Controller\Api\V1\Middleware\NoSql\QueryProcessor\Metric;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class MetricTest
 * @package DatabaseBundle\Tests\Controller\Api\V1\NoSql
 */
class MetricTest extends TestCase
{
    /**
     * @var Metric
     */
    protected $processor;

    /**
     * @var Request
     */
    protected $request;

    public function setUp()
    {
        $this->processor = new Metric();
        $this->request = new Request();
    }

    /**
     * @test
     * @dataProvider validRequestQueryProvider
     */
    public function getQueryParameters(string $json)
    {
        if (! empty($json)) {
            $this->request->query->set('q', $json);
        }

        $this->assertArrayHasKey(
            'periods',
            $this->processor->getQueryParameters($this->request)
        );

        $this->assertArrayHasKey(
            'query',
            $this->processor->getQueryParameters($this->request)
        );
    }

    public function validRequestQueryProvider()
    {
        return [
            [ '{"periods":["now"],"query":{"aggregation":"max","groupBy":{"tags":["test"]},"filter":{"state":["running"]}},"limit":100}' ],
            [ '{"periods":["last24hours"],"query":{"aggregation":"max","groupBy":{"time":"hour"},"filter":{"state":["running"]}},"limit":100}' ],
            [ '{"periods":["last24hours"],"query":{"filter":{"state":["running"]},"limit":10,"columns":["region","availabilityZone","value","minimum","maximum"]}}' ],
            [ '{"periods":["last24hours"],"query":{"aggregation":"max","groupBy":{"time":"hour","tags":["region","availabilityZone"]},"filter":{"state":["running"]}},"limit":100}' ],
            [ '{"periods":["last24hours"],"query":{"aggregation":"sum","groupBy":{"time":"hour"},"query":{"aggregation":"max","groupBy":{"time":"hour","tags":["region","availabilityZone"]},"filter":{"state":["running"]}},"limit":100}}' ],
            [ '{"periods":["now"]}' ],
            [ '{"query":{}}' ],
        ];
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @dataProvider invalidRequestQueryProvider
     */
    public function getQueryParametersInvalidRequest(string $json)
    {
        $this->request->query->set('q', $json);
        $this->processor->getQueryParameters($this->request);
    }

    public function invalidRequestQueryProvider()
    {
        return [
            [ '{"periods":["last24hours"],"query":{"query":{"query":{"query":{"query":{"query":{"query":{}}}}}}}}' ],
            [ '{"periods":"now","query":{"groupBy":{"time":"hour"}}}' ],
        ];
    }
}
