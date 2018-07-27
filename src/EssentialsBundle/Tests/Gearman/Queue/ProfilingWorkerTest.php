<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 07/12/17
 * Time: 16:57
 */

namespace EssentialsBundle\Tests\Gearman\Queue;

use EssentialsBundle\Collection\Metric\MetricCollection;
use EssentialsBundle\Collection\Tag\TagCollection;
use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Entity\Tag\Tag;
use EssentialsBundle\Gearman\Queue\ProfilingWorker;
use EssentialsBundle\Profiler\Analyzer\Job as JobAnalyzer;
use EssentialsBundle\Profiler\Profiler;
use GearmanBundle\Job\Job;
use PHPUnit\Framework\TestCase;

class ProfilingWorkerTest extends TestCase
{
    /**
     * @test
     */
    public function processWithoutValidJob()
    {
        /** @var ProfilingWorker $worker */
        $worker = $this->getMockBuilder(ProfilingWorker::class)
            ->setMethods(null)
            ->disableOriginalConstructor()
            ->getMock();

        $job = $this->getMockBuilder(\GearmanJob::class)
            ->setMethods(['workload'])
            ->disableOriginalConstructor()
            ->getMock();

        $job->expects($this->once())
            ->method('workload')
            ->willReturn(null);

        $this->assertNull($worker->process($job));
    }

    /**
     * @test
     */
    public function processWithoutValidProfiler()
    {
        /** @var ProfilingWorker $worker */
        $worker = $this->getMockBuilder(ProfilingWorker::class)
            ->setMethods(null)
            ->disableOriginalConstructor()
            ->getMock();

        $appJob = $this->getMockBuilder(Job::class)
            ->setMethods(['getData'])
            ->disableOriginalConstructor()
            ->getMock();

        $appJob->expects($this->any())
            ->method('getData')
            ->willReturn(null);

        $job = $this->getMockBuilder(\GearmanJob::class)
            ->disableOriginalConstructor()
            ->setMethods([ 'workload' ])
            ->getMock();

        $job->expects($this->once())
            ->method('workload')
            ->willReturn(serialize($appJob));

        $this->assertNull($worker->process($job));
    }

    /**
     * @test
     */
    public function processEmptyMetricCollection()
    {
        $profiler = new Profiler(new TagCollection([
            new Tag('origin', 'unit.test')
        ]));

        $appJob = $this->getMockBuilder(Job::class)
            ->setMethods(['getData'])
            ->disableOriginalConstructor()
            ->getMock();

        $appJob->expects($this->any())
            ->method('getData')
            ->willReturn($profiler);

        $worker = $this->getMockBuilder(ProfilingWorker::class)
            ->setMethods(null)
            ->disableOriginalConstructor()
            ->getMock();

        $job = $this->getMockBuilder(\GearmanJob::class)
            ->disableOriginalConstructor()
            ->setMethods([ 'workload' ])
            ->getMock();

        $job->expects($this->once())
            ->method('workload')
            ->willReturn(serialize($appJob));

        $account = $this->getMockBuilder(Account::class)
            ->setMethods(null)
            ->disableOriginalConstructor()
            ->getMock();

        $analyzer = $this->getMockBuilder(JobAnalyzer::class)
            ->setMethods(['setProfiler', 'getMetrics'])
            ->disableOriginalConstructor()
            ->getMock();

        $analyzer->expects($this->once())
            ->method('getMetrics')
            ->willReturn(new MetricCollection());

        $analyzer->expects($this->once())
            ->method('setProfiler')
            ->willReturn($analyzer);


        /** @var ProfilingWorker $worker */
        $this->assertNull($worker->process($job, $account, $analyzer));
    }
}
