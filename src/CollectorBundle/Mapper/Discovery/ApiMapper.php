<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 30/10/17
 * Time: 17:38
 */

namespace CollectorBundle\Mapper\Discovery;

use CollectorBundle\Collect\CollectBucket;
use CollectorBundle\Collect\Discovery\Collect;
use CollectorBundle\Collect\Discovery\CollectCollection;
use CollectorBundle\Collect\Parameter;
use CollectorBundle\Collect\ParameterCollection;
use CollectorBundle\Job\JobBuilder;
use DataSourceBundle\Api\V1\DataSourceCollect\ParametersResolver;
use DataSourceBundle\Entity\DataSource\DataSource;
use Doctrine\Common\Persistence\ObjectManager;
use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\Exception\Exceptions;
use EssentialsBundle\Profiler\Profiler;

class ApiMapper
{
    /**
     * @var ParametersResolver
     */
    protected $resolver;

    /**
     * @var JobBuilder
     */
    protected $builder;

    /**
     * @var ObjectManager
     */
    protected $em;

    /**
     * ApiMapper constructor.
     * @param ParametersResolver $resolver
     * @param JobBuilder $jobBuilder
     * @param ObjectManager $em
     */
    public function __construct(
        ParametersResolver $resolver,
        JobBuilder $jobBuilder,
        ObjectManager $em
    ) {
        $this->resolver = $resolver;
        $this->builder = $jobBuilder;
        $this->em = $em;
    }

    public function toDiscoveryCollectBucket(
        Account $account,
        DateTime $time,
        array $data,
        Profiler $profiler = null
    ): CollectBucket {
        $bucket = new CollectBucket($account, $time, new CollectCollection());
        foreach ($data as $arr) {
            $ds = $this->getDataSource($arr['dataSource']['name']);
            $parameters = (is_array($arr['parameters']))
                ? $arr['parameters']
                : [];

            $parameters = $this->getResolvedParameters(
                $account,
                $ds,
                $parameters
            );

            /*
             * If a data source can be built it's valid.
             */
            $collect = $this->getCollect($ds, $parameters);
            $collect->getPendingJobs()->add(
                $this->builder->factory($account, $time, $collect, $profiler)
            );
            $bucket->getCollects()->add($collect);
        }

        return $bucket;
    }

    protected function getResolvedParameters(
        Account $account,
        DataSource $ds,
        array $parameters
    ) {
        return $this->resolver->process(
            $account,
            $ds,
            $parameters
        );
    }

    protected function getCollect(
        DataSource $ds,
        array $parameters
    ): Collect {
        $collection = new ParameterCollection();

        foreach ($parameters as $name => $value) {
            $collection->add(new Parameter($name, $value));
        }

        $collect = new Collect(
            new \CollectorBundle\Collect\DataSource(
                $ds->getName(),
                $ds->getDataSourceConfiguration()->getMaxConcurrency(),
                $ds->getDataSourceConfiguration()->getCollectInterval()
            ),
            $collection
        );

        return $collect;
    }

    protected function getDataSource(string $name)
    {
        $ds = $this->em->getRepository(DataSource::class)
            ->findOneBy(['name' => $name]);

        if (empty($ds)) {
            throw new \InvalidArgumentException(
                "Data source " . $name . " not found.",
                Exceptions::RESOURCE_NOT_FOUND
            );
        }

        return $ds;
    }
}
