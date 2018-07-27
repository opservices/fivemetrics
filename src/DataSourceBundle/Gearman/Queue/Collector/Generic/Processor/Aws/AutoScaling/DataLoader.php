<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 13/03/17
 * Time: 15:56
 */

namespace DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScaling;

use DataSourceBundle\Collection\Aws\AutoScaling\ActivityCollection;
use DataSourceBundle\Collection\Aws\AutoScaling\AutoScalingGroupCollection;
use DataSourceBundle\Aws\EC2\EC2 as EC2Client;
use Doctrine\Common\Cache\CacheProvider;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScaling\Job\Job;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\DataLoaderAbstract;

/**
 * Class DataLoader
 * @package DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScaling
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
     * @param string $groupName
     * @return ActivityCollection
     */
    public function retrieveAutoScalingActivities(
        string $groupName = null
    ): ActivityCollection {
        $key = $this->getJob()->getRegion()->getCode()
            . __CLASS__ . __METHOD__;

        $data = $this->cacheFetch($key);
        if ($data) {
            return $data;
        }

        $data = $this->getEC2Client()
            ->retrieveAutoScalingActivities($groupName);

        $this->cacheWrite($key, $data);

        return $data;
    }

    /**
     * @return AutoScalingGroupCollection
     */
    public function retrieveAutoScalingGroups(): AutoScalingGroupCollection
    {
        $key = $this->getJob()->getRegion()->getCode()
            . __CLASS__ . __METHOD__;

        $data = $this->cacheFetch($key);
        if ($data) {
            return $data;
        }

        $data = $this->getEC2Client()
            ->retrieveAutoScalingGroups();

        $this->cacheWrite($key, $data);

        return $data;
    }
}
