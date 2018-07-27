<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 25/09/17
 * Time: 15:20
 */

namespace CollectorBundle\Collect;

use CollectorBundle\Collect\Discovery\Collect as DiscoveryCollect;
use CollectorBundle\Collect\Discovery\CollectCollection as DiscoveryCollectCollection;
use CollectorBundle\Job\JobBuilder;
use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Entity\Account\AccountBuilder;
use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\Profiler\Profiler;

class CollectBucketBuilder
{
    /**
     * @var AccountBuilder
     */
    protected $accountBuilder;

    /**
     * @var JobBuilder
     */
    protected $jobBuilder;

    /**
     * CollectBucketBuilder constructor.
     * @param AccountBuilder $accountBuilder
     * @param JobBuilder $jobBuilder
     */
    public function __construct(
        AccountBuilder $accountBuilder,
        JobBuilder $jobBuilder
    ) {
        $this->accountBuilder = $accountBuilder;
        $this->jobBuilder = $jobBuilder;
    }

    /**
     * @param array $data
     * @param bool $discoveryCollect
     * @param Profiler|null $profiler
     * @return CollectBucketCollection
     */
    public function factory(
        array $data,
        bool $discoveryCollect = false,
        Profiler $profiler = null
    ): CollectBucketCollection {
        $collection = new CollectBucketCollection();

        foreach ($data as $bucket) {
            $time = DateTime::createFromFormat(
                \DateTime::RFC3339,
                $bucket['time']
            );

            $account = $this->getAccountInstance($bucket['account']);
            $collects = $this->buildCollectCollection(
                clone($account),
                $time,
                $bucket['collects'],
                $discoveryCollect,
                $profiler
            );

            $bucket = $this->getCollectBucketInstance(
                clone($account),
                $time,
                clone($collects)
            );

            $collection->add(clone($bucket));
            unset($bucket, $account, $collects);
        }

        return $collection;
    }

    protected function getAccountInstance(array $data): Account
    {
        return $this->accountBuilder->factory($data);
    }

    protected function buildCollectCollection(
        Account $account,
        DateTime $time,
        array $collects,
        bool $discoveryCollect,
        Profiler $profiler = null
    ): CollectCollection {
        $collection = ($discoveryCollect)
            ? new DiscoveryCollectCollection()
            : new CollectCollection();

        foreach ($collects as $collectData) {
            $collection->add(
                $this->getCollectInstance($collectData, $discoveryCollect)
            );

            $collect = $collection->at(count($collection) - 1);

            $collect->getPendingJobs()
                ->push(
                    $this->jobBuilder->factory($account, $time, $collect, $profiler)
                );
        }

        return $collection;
    }

    protected function getCollectInstance(array $data, bool $discoveryCollect)
    {
        $lastUpdate = (isset($data['lastUpdate']))
            ? DateTime::createFromFormat(
                \DateTime::RFC3339,
                $data['lastUpdate']
            )
            : null;

        return ($discoveryCollect)
            ? new DiscoveryCollect(
                $this->getDataSourceInstance($data['dataSource']),
                $this->buildParameterCollection($data['parameters'])
            )
            : new Collect(
                $data['id'],
                $this->getDataSourceInstance($data['dataSource']),
                $this->buildParameterCollection($data['parameters']),
                $data['isEnabled'],
                $lastUpdate
            );
    }

    protected function buildParameterCollection(
        array $parameters
    ): ParameterCollection {
        $collection = new ParameterCollection();

        foreach ($parameters as $parameter) {
            $collection->add(new Parameter(
                $parameter['name'],
                $parameter['value']
            ));
        }

        return $collection;
    }

    protected function getDataSourceInstance(array $ds): DataSource
    {
        return new DataSource(
            $ds['name'],
            $ds['maxConcurrency'],
            $ds['collectInterval']
        );
    }

    protected function getCollectBucketInstance(
        Account $account,
        DateTime $time,
        CollectCollection $collects
    ) {
        return new CollectBucket(
            $account,
            $time,
            $collects
        );
    }
}
