<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 13/03/17
 * Time: 15:56
 */

namespace DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EC2;

use DataSourceBundle\Collection\Aws\EC2\Instance\InstanceIndexer;
use DataSourceBundle\Collection\Aws\EC2\Reservation\Instance\ReservationCollection;
use DataSourceBundle\Collection\Aws\EC2\Instance\InstanceCollection;
use DataSourceBundle\Collection\Aws\EC2\Subnet\SubnetCollection;
use DataSourceBundle\Aws\EC2\EC2 as EC2Client;
use Doctrine\Common\Cache\CacheProvider;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\DataLoaderAbstract;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EC2\Job\Job;

/**
 * Class DataLoader
 * @package DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EC2
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

    public function __destruct()
    {
        $this->client = null;
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
     * @return ReservationCollection
     */
    public function retrieveReservedInstances(): ReservationCollection
    {
        $key = $this->getJob()->getRegion()->getCode()
            . __CLASS__ . __METHOD__;

        $data = $this->cacheFetch($key);

        if ($data) {
            return $data;
        }

        $data = $this->getEC2Client()
            ->retrieveReservedInstances();

        $this->cacheWrite($key, $data);

        return $data;
    }

    /**
     * @param bool $indexed
     * @return InstanceCollection
     */
    public function retrieveInstances(bool $indexed = false): InstanceCollection
    {
        $key = sprintf(
            "%s-%s-%s",
            $this->getJob()->getRegion()->getCode(),
            __CLASS__ . __METHOD__,
            ($indexed) ? '-indexed' : ''
        );

        $data = $this->cacheFetch($key);
        if ($data) {
            return $data;
        }

        if ($indexed) {
            $data = $this->getEC2Client()->retrieveInstances(
                new InstanceCollection([], new InstanceIndexer())
            );
        } else {
            $data = $this->getEC2Client()->retrieveInstances();
        }

        $this->cacheWrite($key, $data);

        return $data;
    }

    /**
     * @return SubnetCollection
     */
    public function retrieveSubnets(): SubnetCollection
    {
        $key = $this->getJob()->getRegion()->getCode()
            . __CLASS__ . __METHOD__;

        $data = $this->cacheFetch($key);

        if ($data) {
            return $data;
        }

        $data = $this->getEC2Client()
            ->retrieveSubnets();

        $this->cacheWrite($key, $data);

        return $data;
    }
}
