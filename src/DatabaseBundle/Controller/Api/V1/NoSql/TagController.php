<?php

namespace DatabaseBundle\Controller\Api\V1\NoSql;

use DatabaseBundle\Controller\Api\V1\Middleware\NoSql\QueryProcessor\Tag;
use EssentialsBundle\Api\ControllerAbstract;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Swagger\Annotations as SWG;

/**
 * Class TagController
 * @package DatabaseBundle\Controller\Api\V1\NoSql
 * @Route("/tags")
 */
class TagController extends ControllerAbstract
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/metrics/", name="metricsTags")
     * @Method({"GET"})
     * @SWG\Get(
     *     summary="Retrieve the metric tags.",
     *     @SWG\Parameter(
     *          name="q",
     *          in="query",
     *          type="string",
     *          description="A metric list with the desired tag type [all|system|custom].<br>
    <h3>Query format</h3>
    <code>{""metrics"":[""aws.ec2.instances"",""aws.ec2.ebs""],""type"":""all""}</code><br>
    <h6>Allowed values for type</h6>
    <em>all, system, custom</em><br><br>
    ",
     *     ),
     *     @SWG\Response(
     *          response=200,
     *          description="It will return a list with metric and tags<br>
    <h3>Response format</h3>
    [{""name"":""metric name"",""tags"":{""system"":[{""name"":""tag name"",""values"":[""tag values""]}]}}]<br>
    ",
     *     )
     * )
     */
    public function metricsTagsAction(Request $request)
    {
        $query = (new Tag())->getQueryParameters($request);

        $metricsController = $this->get('app.metric.controller');
        $metricsController->setContainer($this->container);

        $response = [];
        $emptySeries = [
            'name' => '',
            'tags' => [
                'system' => [],
                'custom' => [],
            ],
        ];

        foreach ($query['metrics'] as $metric) {
            $emptySeries['name'] = $metric;
            $response[] = $emptySeries;

            $metricTags = json_decode(
                $metricsController->getMetricAction($metric)->getContent(),
                true
            );

            if (empty($metricTags)) {
                continue;
            }

            $systemTags = [];
            $customTags = [];

            foreach ($metricTags['tags'] as $idx => $tag) {
                (substr($tag['name'], 0, 6) == '::fm::')
                    ? $systemTags[] = $tag
                    : $customTags[] = $tag;
            }

            $last = count($response) - 1;
            $response[$last]['tags'] = [
                'system' => ($query['type']['system']) ? $systemTags : [],
                'custom' => ($query['type']['custom']) ? $customTags : [],
            ];
        }

        return $this->createApiResponse($response);
    }
}
