<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 8/15/17
 * Time: 10:04 AM
 */

namespace DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EBS;

use DataSourceBundle\Aws\EC2\EC2 as EC2Client;
use DataSourceBundle\Collection\Aws\EBS\Volume\VolumeCollection;
use DataSourceBundle\Collection\Aws\EC2\Instance\InstanceCollection;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\DataLoaderAbstract;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EBS\Job\Job;
use Doctrine\Common\Cache\CacheProvider;

/**
 * Class DataLoader
 * @package DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EBS
 */
class DataLoader extends DataLoaderAbstract
{

    /**
     * @var EC2Client
     */
    protected $client;

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
     * @param array $filter
     * @return VolumeCollection
     */
    public function retrieveVolumes(array $filter = []): VolumeCollection
    {
        $key = $this->getJob()->getRegion()->getCode()
            . __CLASS__
            . __METHOD__
            . $this->filterMd5($filter);

        $data = $this->cacheFetch($key);

        if ($data) {
            return $data;
        }

        $data = $this->getEC2Client()
            ->retrieveVolumes();

        $this->cacheWrite($key, $data);

        return $data;
    }

    /**
     * @param array $filter
     * @return string
     */
    protected function filterMd5(array $filter): string
    {
        $tmp = array_map(function ($el) {
            return md5(implode(
                '',
                array_values($el)
            ));
        }, $filter);

        return md5(implode('', $tmp));
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
