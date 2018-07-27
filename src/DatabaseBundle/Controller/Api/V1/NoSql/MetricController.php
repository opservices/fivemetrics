<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 19/05/17
 * Time: 17:13
 */

namespace DatabaseBundle\Controller\Api\V1\NoSql;

use DatabaseBundle\Controller\Api\V1\Middleware\NoSql\QueryProcessor\Metric;
use DatabaseBundle\RealTime\Storage;
use DataSourceBundle\Aws\CostExplorer\CostExplorer;
use DataSourceBundle\Aws\CostExplorer\GranularityEnum;
use DataSourceBundle\Aws\CostExplorer\Parser\ResultSet;
use DataSourceBundle\Entity\DataSource\DataSourceParameter;
use EssentialsBundle\Api\ControllerAbstract;
use EssentialsBundle\Api\ResultTrait;
use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\Entity\TimePeriod\TimePeriodProvider;
use InfluxDB\Client;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MetricController
 * @package DatabaseBundle\Controller\Api\V1\NoSql
 * @Route("/metrics")
 */
class MetricController extends ControllerAbstract
{
    use ResultTrait;
    /**
     * @param string|null $database
     * @return Client
     */
    protected function getNoSqlClient(string $database = null): Client
    {
        return $this->get('nosql.database.connection.provider')
            ->getConnection($database)
            ->getClient();
    }

    /**
     * @Route("/", name="apiListMetrics")
     * @Method({"GET"})
     * @SWG\Get(
     *     summary="List the stored metrics",
     *     @SWG\Response(
     *          response=200,
     *          description="It will return a list with the metric description",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="name", type="string",),
     *              @SWG\Property(
     *                  property="tags",
     *                  type="object",
     *                  @SWG\Property(
     *                      property="name",
     *                      description="The tag name.",
     *                      type="string",
     *                  ),
     *                  @SWG\Property(
     *                      property="values",
     *                      description="Each value is stored on this tag name.",
     *                      type="array",
     *                      @SWG\Items(type="string",),
     *                  ),
     *              ),
     *          ),
     *     )
     * )
     */
    public function listMetricsAction($metricName = null)
    {
        $query = (is_null($metricName))
            ? 'SHOW TAG VALUES FROM /.*/ WITH KEY !~ /(instanceId|volumeId|jobId|subnetId)$/'
            : 'SHOW TAG VALUES FROM "' . $metricName . '" WITH KEY !~ /(instanceId|volumeId|jobId|subnetId)$/';

        $database = $this->getUser()->getUid();

        $metrics = $this->getNoSqlClient($database)
            ->query($database, $query)
            ->getSeries();

        if (empty($metrics[0])) {
            return $this->createApiResponse(
                [],
                Response::HTTP_NOT_FOUND
            );
        }

        $metrics = array_map(function ($item) {
            $tags = [];
            $tagIdx = [];
            foreach ($item['values'] as $tag) {
                if (isset($tagIdx[$tag[0]])) {
                    $idx = $tagIdx[$tag[0]];

                    if (! in_array(urldecode($tag[1]), $tags[$idx]['values'])) {
                        $tags[$idx]['values'][] = urldecode($tag[1]);
                    }

                    continue;
                }

                $tagIdx[$tag[0]] = count($tags);
                $idx = $tagIdx[$tag[0]];
                $tags[$idx] = [
                    'name' => urldecode($tag[0]),
                    'values' => [ urldecode($tag[1]) ],
                ];
            }

            return [
                'name' => $item['name'],
                'tags' => $tags
            ];
        }, $metrics);

        if (! is_null($metricName)) {
            $metrics = $metrics[0];
        }

        return $this->createApiResponse($metrics);
    }

    /**
     * @param string $metricName
     * @Route("/{metricName}", name="apiGetMetricPoints")
     * @Method({"GET"})
     * @SWG\Get(
     *     summary="Retrieve a metric description.",
     *     @SWG\Parameter(
     *          name="metricName",
     *          in="path",
     *          type="string",
     *          description="The metric name.",
     *     ),
     *     @SWG\Response(
     *          response=200,
     *          description="It will return a metric description",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="name", type="string",),
     *              @SWG\Property(
     *                  property="tags",
     *                  type="object",
     *                  @SWG\Property(
     *                      property="name",
     *                      description="The tag name.",
     *                      type="string",
     *                  ),
     *                  @SWG\Property(
     *                      property="values",
     *                      description="Each value is stored on this tag name.",
     *                      type="array",
     *                      @SWG\Items(type="string",),
     *                  ),
     *              ),
     *          ),
     *     )
     * )
     */
    public function getMetricAction($metricName)
    {
        return $this->listMetricsAction($metricName);
    }

    /**
     * @param Request $request
     * @param string $metricName
     * @Route("/{metricName}/history", name="apiGetHistoryPoints")
     * @Method({"GET"})
     * @SWG\Get(
     *     summary="Retrieve the historical data of a metric.",
     *     @SWG\Parameter(
     *          name="metricName",
     *          in="path",
     *          type="string",
     *          description="The metric name.",
     *     ),
     *     @SWG\Parameter(
     *          name="q",
     *          in="query",
     *          type="string",
     *          description="The ""q"" parameter is the filter used to search the metric data.<br>
    It is possible build a query using up to six nested query properties.<br><br>
    <h3>Filter format</h3>
    Default filter<br>
    <code>{""periods"":[""now""],""query"":{}}</code><br>
    <h4>Periods</h4>
    When more than one period is provided in a single request they are used in order until any data is found. If some data is found the used period is returned inside the response body as value of ""period"" property. Otherwise the ""period"" will be empty.<br><br>
    <h6>Allowed values</h6>
    <em>now, lastminute, last5minutes, last10minutes, last15minutes, last30minutes, lasthour, last24hours, last7days, lastweek, lastmonth, last31days, lastyear, thishour, thismonth, thisweek, thisyear, today, yesterday</em><br><br>
    Example:
    <code>{""periods"":[""lastminute"", ""last5minutes"", last10minutes""],""query"":{}}</code><br>
    <h4>Query</h4>
    This property defines the filter that will be used to search the metric data. It is possible to process the query result in another query using nested query.<br><br>
    Example
    <code>{""periods"":[""last24hours""],""query"":{""query"":{""limit"":500},""limit"":250}}</code><br>
    <h5>Aggregation</h5>
    <h6>Allowed values</h6>
    <em>count, min, max, sum, mean</em><br><br>
    Example:<br>
    <code>{""query"":{""aggregation"":""max""}}</code><br>
    <h5>Filter</h5>
    The filter property is an object where each key should be a metric tag and each value is a list of desired tag values.<br>
    If you want to search by values of metrics with a tag called ""city"" and another called ""company"" the filter will be something like this:<br>
    <code>{""query"":{""filter"":{""city"":[""Porto Alegre"",""São Paulo""],""company"":[""OpServices""]}}}</code>
    <br>The filter above will search by any point with ""city"" equals to ""Porto Alegre"" or ""São Paulo"" and ""company"" equals to ""OpServices"". Each new filter key will be translated in a new ""OR"" and each new value will be translated in a new ""AND"".<br>
    <h5>Fill</h5>
    The fill property should be used when null values aren't allowed.<br>
    <code>{""query"":{""filter"":{""city"":[""Porto Alegre"",""São Paulo""],""company"":[""OpServices""]},""fill"":0}}</code>
    <h6>Allowed values</h6>
    <em>Only numbers.</em><br><br>
    <h5>Group By</h5>
    The ""groupBy"" property is used to group the query data. You can do it using a time interval, one or more tags or both.<br>
    <h4>time</h4>
    To use group by time you must define an aggregation method.
    <h6>Allowed values</h6>
    <em>second, minute, hour, day</em><br><br>
    <h4>tags</h4>
    It is an array with one or more metric tag. Each new tag combination will result in a new returned serie.<br>
    Example:<br>
    <code>{""periods"":[""last24hours""],""query"":{""aggregation"":""max"",""groupBy"":{""time"":""hour"",""tags"":[""::fm::region"",""::fm::availabilityZone""]}}}</code><br>
    <h5>Order By</h5>
    You can choose if you want to retrieve the newest or the oldest data using the property ""orderBy"".<br><br>
    Default value
    <code>{""query"":{""orderBy"":""newest""}}</code><br>
    <h6>Allowed values</h6>
    <em>newest, oldest</em><br><br>
    <h5>Limit</h5>
    This property is a limit to the returned points by serie. The maximum allowed is 1000.<br><br>
    Default value
    <code>{""periods"":[""last24hours""],""query"":{""limit"":250}}</code><br>
    <h5>Columns</h5>
    With this property you can choose the returned columns.<br><br>
    Example:<br>
    <code>{""query"":{""columns"":[""value"",""minimum"",""maximum"",""unit""]}}</code><br>
    ",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="periods",
     *                  description="Each value is stored on this tag name.",
     *                  type="array",
     *                  enum={"now", "last5minutes"},
     *                  @SWG\Items(type="string",),
     *              ),
     *          ),
     *     ),
     *     @SWG\Response(
     *          response=200,
     *          description="It will return a metric description",
     *     )
     * )
     * @return Response
     */
    public function getMetricHistoryAction(Request $request, $metricName)
    {
        $processor = new Metric();

        $params = $processor->getQueryParameters($request);
        $periods = $params['periods'];
        $database = $this->getUser()->getUid();
        $points = [];

        foreach ($periods as $period) {
            $params['period'] = $period;

            $points = $this->get('nosql.metric.repository')
                ->getHistory($database, $metricName, $params);

            if (isset($points['series'][0])) {
                $points['period'] = $period;
                break;
            }
        }

        (isset($points['period'])) ?: $points = ['series' => [], 'period' => ''];

        return $this->createApiResponse(
            $points,
            (empty($points['series'])) ? Response::HTTP_NOT_FOUND : Response::HTTP_OK
        );
    }

    /**
     * @param Request $request
     * @param string $metricName
     * @Route("/{metricName}/realtime/", name="apiGetRealtimePoints")
     * @Method("GET")
     * @return Response
     */
    public function getMetricRealTimeAction(Request $request, $metricName)
    {
        if ('aws.ec2.reserves' == $metricName) {
            return $this->getAwsEc2Reserves($request, $metricName);
        }

        $query = $cacheId = $request->query->get('q', '{}');
        $query = json_decode($query, true);
        $cache = $this->getCacheFactory()->factory($this->getUser());

        $resultset = $cache->fetch(md5($cacheId));

        if (! ($resultset instanceof ResultSet)) {
            $repo = $this->getDoctrine()->getManager()
                ->getRepository(DataSourceParameter::class);

            $parameters = [];

            /** @var DataSourceParameter $parameter */
            foreach ($repo->findByAccount($this->getUser()) as $parameter) {
                $parameters[$parameter->getName()] = $parameter->getValue();
            }

            $start = new DateTime('first day of this month');
            $end = new DateTime('last day of this month');
            $client = new CostExplorer(
                $parameters['aws.key'],
                $parameters['aws.secret'],
                (new TimePeriodProvider)->getCustomTimePeriod($start, $end)
            );

            if (! isset($query['groupBy']['time'])) {
                $resultset = $client->getCostByService();
            } elseif ('day' == $query['groupBy']['time']) {
                $resultset = $client->getCost(GranularityEnum::DAILY);
            } else {
                $resultset = $client->getCost(GranularityEnum::MONTHLY);
            }

            $cache->save(md5($cacheId), $resultset, 24*60*60);
        }

        $result = $resultset->toArray();
        $result = $this->createResult(
            'realtime',
            $metricName,
            $result['amount'],
            [],
            $result
        );

        return $this->createApiResponse($result);
    }

    /**
     * @param Request $request
     * @param string $metricName
     * @return JsonResponse
     */
    protected function getAwsEc2Reserves(Request $request, $metricName): JsonResponse
    {
        $requestedFilter = $request->query->get('q', '{"filter": []}');
        $requestedFilter = json_decode($requestedFilter, true);

        if (!isset($requestedFilter['filter'])) {
            $this->throwApiProblemResponse(Response::HTTP_BAD_REQUEST, ['Invalid filter query']);
        }

        $cache = $this->getCacheFactory()->factory($this->getUser());

        $storage = new Storage($cache);

        $cachedData = $storage->fetch($metricName, $requestedFilter['filter']);
        $cachedData = array_reduce($cachedData, 'array_merge', []);

        $result = $this->createResult('realtime', $metricName, count($cachedData), $cachedData);
        return $this->createApiResponse($result);
    }
}
