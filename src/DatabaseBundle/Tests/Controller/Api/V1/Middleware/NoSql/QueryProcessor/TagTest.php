<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 05/06/17
 * Time: 15:48
 */

namespace DatabaseBundle\Tests\Controller\Api\V1\Middleware\NoSql\QueryProcessor;

use DatabaseBundle\Controller\Api\V1\Middleware\NoSql\QueryProcessor\Tag;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class MetricTest
 * @package DatabaseBundle\Tests\Controller\Api\V1\NoSql
 */
class TagTest extends TestCase
{
    /**
     * @var Tag
     */
    protected $processor;

    /**
     * @var Request
     */
    protected $request;

    public function setUp()
    {
        $this->processor = new Tag();
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

        $query = $this->processor->getQueryParameters($this->request);

        $this->assertArrayHasKey('metrics', $query);
        $this->assertArrayHasKey('type', $query);
    }

    public function validRequestQueryProvider()
    {
        return [
            [ '{"metrics":["aws.ec2.instances","aws.ec2.ebs"],"type":"all"}' ],
            [ '{"metrics":["junk"],"type":"custom"}' ],
            [ '{"metrics":["junk"],"type":"system"}' ],
            [ '{"metrics":["junk"]}' ],
            [ '{"metrics":["junk","aws.ec2.instances"],"invalidAttribute"}' ],
            [ '' ],
        ];
    }
}
