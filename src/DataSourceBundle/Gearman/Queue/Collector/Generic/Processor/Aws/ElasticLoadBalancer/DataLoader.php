<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 13/03/17
 * Time: 15:56
 */

namespace DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer;

use DataSourceBundle\Collection\Aws\ElasticLoadBalancer\ElasticLoadBalancerCollection;
use DataSourceBundle\Collection\Aws\ElasticLoadBalancer\InstanceHealthCollection;
use DataSourceBundle\Collection\Aws\EC2\Instance\InstanceCollection;
use DataSourceBundle\Aws\EC2\EC2 as EC2Client;
use Doctrine\Common\Cache\CacheProvider;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\DataLoaderAbstract;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer\Job\Job;

/**
 * Class DataLoader
 * @package DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer
 */
class DataLoader extends DataLoaderAbstract
{
    /**
     * @var EC2Client
     */
    protected $client;

    /**
     * DataLoader constructor.
     * @param Job $job
     * @param CacheProvider|null $cacheProvider
     * @param EC2Client|null $ec2Client
     */
    public function __construct(
        Job $job,
        CacheProvider $cacheProvider = null,
        EC2Client $ec2Client = null
    ) {
        parent::__construct($job, $cacheProvider);
        $this->client = $ec2Client;
    }

    /**
     * @return EC2Client
     */
    protected function getEC2Client()
    {
        if (is_null($this->client)) {
            $this->client = new EC2Client(
                $this->getJob()->getKey(),
                $this->getJob()->getSecret(),
                $this->getJob()->getRegion()
            );
        }

        return $this->client;
    }

    /**
     * @return ElasticLoadBalancerCollection
     */
    public function retrieveElasticLoadBalancers(): ElasticLoadBalancerCollection
    {
        $key = $this->getJob()->getRegion()->getCode()
            . __CLASS__ . __METHOD__;

        $data = $this->cacheFetch($key);
        if ($data) {
            return $data;
        }

        $data = $this->getEC2Client()
            ->retrieveElasticLoadBalancers();

        $this->cacheWrite($key, $data);

        return $data;
    }

    /**
     * @param string $loadBalancerName
     * @return InstanceHealthCollection
     */
    public function retrieveElasticLoadBalancerInstanceHealth(
        string $loadBalancerName
    ): InstanceHealthCollection {
        $key = $this->getJob()->getRegion()->getCode()
            . __CLASS__ . __METHOD__;

        $data = $this->cacheFetch($key);
        if ($data) {
            return $data;
        }

        $data = $this->getEC2Client()
            ->retrieveElasticLoadBalancerInstanceHealth($loadBalancerName);

        $this->cacheWrite($key, $data);

        return $data;
    }

    /**
     * @return InstanceCollection
     */
    public function retrieveInstances(): InstanceCollection
    {
        $key = $this->getJob()->getRegion()->getCode()
            . __CLASS__ . __METHOD__;

        $data = $this->cacheFetch($key);
        if ($data) {
            return $data;
        }

        $data = $this->getEC2Client()->retrieveInstances();

        $this->cacheWrite($key, $data);

        return $data;
    }
}
