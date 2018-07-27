<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 08/12/17
 * Time: 08:43
 */

namespace DatabaseBundle\Controller\Api\V1\NoSql\System;

use DatabaseBundle\Controller\Api\V1\Middleware\NoSql\QueryProcessor\Metric;
use EssentialsBundle\Api\ControllerAbstract;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;

/**
 * Class MonitoringController
 * @package DatabaseBundle\Controller\Api\V1\NoSql\System
 * @Route("/system/metrics")
 */
class MonitoringController extends ControllerAbstract
{
    /**
     * @param Request $request
     * @param $metricName
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Route("/{metricName}/history", name="systemApiGetHistoryPoints")
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
    <h4>Currency</h4>
    It should be used when the point values need be multiplied to generate the currency value.<br>
    Example:<br>
    <code>{""currency"":{""multiplier"": 0.000017743,""precision"":7}}</code><br>
    <h5>Multiplier</h5>
    The cost of one second. This value is used to multiply the query result.
    <h5>Precision</h5>
    The expected precision in each value. The default value is 2.
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
    <code>{""query"":{""filter"":{""city"":[""Porto Alegre"",""São Paulo""],""company"":[""OpServices""],""fill"":0}}}</code>
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
     */
    public function getMetricHistoryAction(Request $request, $metricName)
    {
        $processor = new Metric();

        $params = $processor->getQueryParameters($request);
        $periods = $params['periods'];
        $database = $this->getUser()->getUid();

        $points = ['series' => [], 'period' => ''];

        foreach ($periods as $period) {
            $params['period'] = $period;

            $points = $this->get('nosql.metric.repository')
                ->getHistory($database, $metricName, $params);

            if (isset($points['series'][0])) {
                $points['period'] = $period;
                break;
            }
        }

        if (! isset($params['currency']['multiplier'])) {
            return $this->createApiResponse(
                $points,
                (empty($points['series'])) ? Response::HTTP_NOT_FOUND : Response::HTTP_OK
            );
        }


        $multiplier = $params['currency']['multiplier'];
        $precision = $params['currency']['precision'] ?? 2;
        foreach ($points['series'] as &$series) {
            foreach ($series['points'] as &$point) {
                $point['value'] = round($point['value'] * $multiplier, $precision);
            }

            $series['minimum'] = round($series['minimum'] * $multiplier, $precision);
            $series['maximum'] = round($series['maximum'] * $multiplier, $precision);
        }

        return $this->createApiResponse(
            $points,
            (empty($points['series'])) ? Response::HTTP_NOT_FOUND : Response::HTTP_OK
        );
    }
}
