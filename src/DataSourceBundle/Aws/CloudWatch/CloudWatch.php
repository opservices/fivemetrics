<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 10/02/17
 * Time: 17:38
 */

namespace DataSourceBundle\Aws\CloudWatch;

use Aws\CloudWatch\CloudWatchClient;
use EssentialsBundle\Collection\Metric\MetricCollection;
use DataSourceBundle\Aws\ClientAbstract;
use DataSourceBundle\Entity\Aws\CloudWatch\Builder;
use DataSourceBundle\Entity\Aws\CloudWatch\MetricStatistic;
use DataSourceBundle\Entity\Aws\Region\RegionInterface;

/**
 * Class CloudWatch
 * @package DataSource\Aws\CloudWatch
 */
class CloudWatch extends ClientAbstract
{
    const CLOUD_WATCH_CLIENT_VERSION = '2010-08-01';

    /**
     * @var CloudWatchClient
     */
    protected $cwCli;

    /**
     * CloudWatch constructor.
     * @param string $key
     * @param string $secret
     * @param RegionInterface $region
     */
    public function __construct($key, $secret, RegionInterface $region)
    {
        parent::__construct($key, $secret, $region);

        $this->cwCli = new CloudWatchClient([
            "region"      => $region->getCode(),
            "version"     => self::CLOUD_WATCH_CLIENT_VERSION,
            "credentials" => $this->getCredential()
        ]);
    }

    /**
     * @return CloudWatchClient
     */
    public function getCloudWatchClient(): CloudWatchClient
    {
        return $this->cwCli;
    }

    /**
     * @see http://docs.aws.amazon.com/aws-sdk-php/v3/api/api-monitoring-2010-08-01.html#listmetrics
     * @param array $filter
     * @return array
     */
    public function listMetrics(array $filter = [])
    {
        $cwMetrics  = [];
        do {
            $response   = $this->getCloudWatchClient()->listMetrics($filter);
            $cwMetrics  = array_merge($cwMetrics, $response['Metrics']);
            $filter[ 'NextToken' ] = $response['NextToken'];
        } while (! empty($response['NextToken']));

        return $cwMetrics;
    }

    /**
     * @param MetricStatistic $metricStatistic
     * @return MetricCollection
     */
    public function getMetricStatistics(
        MetricStatistic $metricStatistic
    ) {
        $data = $this->getCloudWatchClient()
            ->getMetricStatistics($metricStatistic->toArray())
            ->search("* | [1]");

        return (empty($data))
            ? new MetricCollection()
            : Builder::buildMetrics(
                $metricStatistic,
                json_decode(json_encode($data), true)
            );
    }

    /**
     * Verify if the given credential have needed permissions
     *
     * @throw AwsException
     * @return bool
     */
    public function checkCredential(): bool
    {
        return true;
    }
}
