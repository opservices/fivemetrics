<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 24/11/17
 * Time: 08:52
 */

namespace EssentialsBundle\Tests\Gearman\Queue;

use EssentialsBundle\Exception\Dispatcher;
use EssentialsBundle\Gearman\Queue\MailSender;
use EssentialsBundle\Helpers\MailHelper;
use EssentialsBundle\KernelLoader;
use EssentialsBundle\Reflection;
use PHPUnit\Framework\TestCase;

class MailSenderTest extends TestCase
{
    /**
     * @test
     */
    public function processInvalidJob()
    {
        /** @var MailSender $worker */
        $worker = $this->getMockedWorker();

        $dispatcher = $this->getMockBuilder(Dispatcher::class)
            ->disableOriginalConstructor()
            ->setMethods([ 'send' ])
            ->getMock();

        $dispatcher->expects($this->once())
            ->method('send')
            ->willReturn(true);

        Reflection::setPropertyOnObject(
            $worker,
            'errorDispatcher',
            $dispatcher
        );

        $job = $this->getMockedJob('fakeJobData');

        $worker->process($job);
    }

    /**
     * @test
     */
    public function processValidJob()
    {
        /** @var MailSender $worker */
        $worker = $this->getMockedWorker();

        $mailHelper = $this->getMockBuilder(MailHelper::class)
            ->disableOriginalConstructor()
            ->setMethods(['sendMessage'])
            ->getMock();

        $mailHelper->expects($this->once())
            ->method('sendMessage')
            ->willReturn(true);

        $container = KernelLoader::load()->getContainer();
        $container->set(MailHelper::class, $mailHelper);

        Reflection::setPropertyOnObject(
            $worker,
            'container',
            $container
        );

        $job = $this->getMockedJob(new \Swift_Message());

        $worker->process($job);
    }

    protected function getMockedWorker()
    {
        return $this->getMockBuilder(MailSender::class)
            ->disableOriginalConstructor()
            ->setMethods(null)
            ->getMock();
    }

    protected function getMockedJob($workloadData)
    {
        $job = $this->getMockBuilder(\GearmanJob::class)
            ->disableOriginalConstructor()
            ->setMethods([ 'workload' ])
            ->getMock();

        $job->expects($this->once())
            ->method('workload')
            ->willReturn(serialize($workloadData));

        return $job;
    }
}
