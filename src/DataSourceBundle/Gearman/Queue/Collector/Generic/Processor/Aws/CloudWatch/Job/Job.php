<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 21/02/18
 * Time: 08:41
 */

namespace DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\CloudWatch\Job;

use DataSourceBundle\Entity\Aws\CloudWatch\MetricStatistic;
use DataSourceBundle\Entity\Aws\Region\RegionInterface;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\CloudWatch\CloudWatch;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\JobAbstract;
use EssentialsBundle\Entity\Account\AccountInterface;
use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\Profiler\Profiler;

class Job extends JobAbstract
{
    /**
     * Job constructor.
     * @param AccountInterface $account
     * @param DateTime $datetime
     * @param RegionInterface $region
     * @param string $key
     * @param string $secret
     * @param MetricStatistic $data
     * @param Profiler|null $profiler
     */
    public function __construct(
        AccountInterface $account,
        DateTime $datetime,
        RegionInterface $region,
        string $key,
        string $secret,
        MetricStatistic $data,
        Profiler $profiler = null
    ) {
        parent::__construct($account, $datetime, $region, $key, $secret, $data, $profiler);
    }

    /**
     * @return MetricStatistic
     */
    public function getMetricStatistic(): MetricStatistic
    {
        return $this->getData();
    }

    /**
     * @return string
     */
    public function getProcessor(): string
    {
        return 'Aws\\CloudWatch\\CloudWatch';
    }
}
