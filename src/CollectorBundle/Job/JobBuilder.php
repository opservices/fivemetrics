<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 26/09/17
 * Time: 09:33
 */

namespace CollectorBundle\Job;

use CollectorBundle\Collect\Collect;
use CollectorBundle\Collect\Parameter;
use DataSourceBundle\Collection\Aws\CloudWatch\DimensionCollection;
use DataSourceBundle\Entity\Aws\CloudWatch\Dimension;
use DataSourceBundle\Entity\Aws\CloudWatch\MetricStatistic;
use DataSourceBundle\Entity\Aws\Region\RegionProvider;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EC2\Job\Job as EC2Job;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EBS\Job\Job as EBSJob;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer\Job\Job as ELBJob;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\AutoScaling\Job\Job as AutoScalingJob;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\S3\Job\Job as S3Job;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier\Job\Job as GlacierJob;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\CloudWatch\Job\Job as CloudWatchJob;
use EssentialsBundle\Entity\Tag\Tag;
use EssentialsBundle\Entity\TimePeriod\ThisMonth;
use EssentialsBundle\Entity\TimePeriod\TimePeriodInterface;
use EssentialsBundle\Entity\TimePeriod\TimePeriodProvider;
use EssentialsBundle\Exception\Exceptions;
use EssentialsBundle\Profiler\Profiler;
use EssentialsBundle\Entity\Account\AccountInterface;
use EssentialsBundle\Entity\DateTime\DateTime;
use GearmanBundle\Job\JobInterface;

class JobBuilder
{
    protected const JOBS = [
        'aws' => [
            'aws.ec2' => EC2Job::class,
            'aws.ebs' => EBSJob::class,
            'aws.elb' => ELBJob::class,
            'aws.autoscaling' => AutoScalingJob::class,
            'aws.s3' => S3Job::class,
            'aws.glacier' => GlacierJob::class,
            'aws.cloudwatch' => CloudWatchJob::class,
        ],
    ];

    /**
     * @var RegionProvider
     */
    protected $regionProvider;

    /**
     * JobBuilder constructor.
     * @param RegionProvider $regionProvider
     */
    public function __construct(
        RegionProvider $regionProvider
    ) {
        $this->regionProvider = $regionProvider;
    }

    /**
     * @param AccountInterface $account
     * @param DateTime $time
     * @param Collect $collect
     * @param Profiler|null $profiler
     * @return JobInterface
     */
    public function factory(
        AccountInterface $account,
        DateTime $time,
        Collect $collect,
        Profiler $profiler = null
    ): JobInterface {
        $ds = $collect->getDataSource()->getName();

        if (! isset(self::JOBS['aws'][$ds])) {
            throw new \InvalidArgumentException(
                'It\'s unknown the job kind for data source "' . $ds . '".'
            );
        }

        return $this->buildAwsJob($account, $time, $collect, $profiler);
    }

    /**
     * @param AccountInterface $account
     * @param DateTime $time
     * @param Collect $collect
     * @param Profiler|null $profiler
     * @return JobInterface
     */
    protected function buildAwsJob(
        AccountInterface $account,
        DateTime $time,
        Collect $collect,
        Profiler $profiler = null
    ): JobInterface {
        $region = $this->getParameterValue($collect, 'aws.region');
        $region = $this->regionProvider->factory($region);
        $key    = $this->getParameterValue($collect, 'aws.key');
        $secret = $this->getParameterValue($collect, 'aws.secret');
        $ds = $collect->getDataSource()->getName();
        $class  = self::JOBS['aws'][$ds];
        $clonedProfiler = null;

        if ($profiler) {
            /** @var Profiler $clonedProfiler */
            $clonedProfiler = $profiler->clone();
            $clonedProfiler->resetTimers(true);

            $tags = $clonedProfiler->getTags();

            ($tags->find('dataSource'))
                ? $tags->find('dataSource')->setValue($ds)
                : $tags->add(new Tag('dataSource', $ds));

            ($tags->find('account'))
                ? $tags->find('account')->setValue($account->getUid())
                : $tags->add(new Tag('account', $account->getUid()));

            $tags->removeElement($tags->find('aws.region'));
            if (preg_match('/^aws.*/', $ds)) {
                $tags->add(new Tag('aws.region', $region->getCode()));
            }
        }

        return new $class(
            $account,
            $time,
            $region,
            $key,
            $secret,
            ($class == CloudWatchJob::class) ? $this->getMetricStatisticInstance($time, $collect) : null,
            $clonedProfiler
        );
    }

    protected function getMetricStatisticInstance(DateTime $collectTime, Collect $collect): MetricStatistic
    {
        $unit = $collect->getParameters()->find('aws.cloudwatch.unit');
        (is_null($unit)) ?: $unit = $unit->getValue();

        $metricName = $this->getParameterValue($collect, 'aws.cloudwatch.metric_name');
        $namespace = $this->getParameterValue($collect, 'aws.cloudwatch.namespace');


        if (($namespace == 'AWS/Billing') && ($metricName == 'EstimatedCharges')) {
            $time = new ThisMonth();
            $startTime = $time->getStart(TimePeriodInterface::UNIX_TIMESTAMP);
            $endTime = $time->getEnd(TimePeriodInterface::UNIX_TIMESTAMP);
            $period = $endTime - $startTime;
            $period -= $period % 60;
        } else {
            $period = $collect->getDataSource()
                ->getCollectInterval();

            $period -= $period % 60;

            $startTime = $collectTime->getTimestamp();
            $endTime = $collectTime->getTimestamp() - $period;
        }

        $dimensions = new DimensionCollection();
        $data = $this->getParameterValue($collect, 'aws.cloudwatch.dimensions');
        foreach ($data as $dim) {
            $dimensions->add(new Dimension($dim['name'], $dim['value']));
        }

        return new MetricStatistic(
            $this->getParameterValue($collect, 'aws.cloudwatch.namespace'),
            $metricName,
            $dimensions,
            $startTime,
            $endTime,
            $period,
            MetricStatistic::STATISTIC_TYPES,
            $unit
        );
    }

    /**
     * @param Collect $collect
     * @param string $name
     */
    protected function getParameterValue(Collect $collect, string $name)
    {
        /** @var Parameter $parameter */
        $parameter = $collect->getParameters()->find($name);

        if (is_null($parameter)) {
            throw new \InvalidArgumentException(
                'The parameter "' . $name . '" is missing.',
                Exceptions::VALIDATION_ERROR
            );
        }

        return $parameter->getValue();
    }
}
