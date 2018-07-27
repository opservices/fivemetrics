<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 07/12/17
 * Time: 15:37
 */

namespace EssentialsBundle\Tests\EventListener;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use EssentialsBundle\Collection\Tag\TagCollection;
use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Entity\Builder\EntityBuilderProvider;
use EssentialsBundle\Entity\Tag\Tag;
use EssentialsBundle\EventListener\ProfilerDeathSubscriber;
use EssentialsBundle\Profiler\Event\ProfilerDeathEvent;
use EssentialsBundle\Profiler\Profiler;
use GearmanBundle\TaskManager\TaskManager;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Monolog\Logger;

class ProfilerDeathSubscriberTest extends TestCase
{
    /**
     * @test
     */
    public function process()
    {
        $gearmanClient = $this->getMockBuilder(\GearmanClient::class)
            ->setMethods(['addTaskBackground', 'runTasks'])
            ->disableOriginalConstructor()
            ->getMock();

        $gearmanClient->expects($this->once())
            ->method('addTaskBackground')
            ->willReturn(true);

        $gearmanClient->expects($this->once())
            ->method('runTasks')
            ->willReturn(true);

        $tm = $this->getMockBuilder(TaskManager::class)
            ->setMethods(['getClient'])
            ->disableOriginalConstructor()
            ->getMock();

        $tm->expects($this->exactly(2))
            ->method('getClient')
            ->willReturn($gearmanClient);

        $account = EntityBuilderProvider::factory(Account::class)
            ->factory([
                'email' => 'tester@fivemetrics.io',
                'username' => 'tester',
            ], []);

        $em = $this->getMockBuilder(EntityManager::class)
            ->setMethods(null)
            ->disableOriginalConstructor()
            ->getMock();

        $logger = $logger = $this->getMockBuilder(Logger::class)
            ->setMethods(null)
            ->disableOriginalConstructor()
            ->getMock();

        $subscriber = new ProfilerDeathSubscriber(
            $tm,
            $logger,
            $em,
            $account
        );

        $profiler = new Profiler(new TagCollection([
            new Tag('origin', 'unit.test')
        ]));

        $event = new ProfilerDeathEvent($profiler);

        $subscriber->process($event);
    }

    /**
     * @test
     * @expectedException \RuntimeException
     */
    public function processWithoutSystemAccount()
    {
        $tm = $this->getMockBuilder(TaskManager::class)
            ->setMethods(null)
            ->disableOriginalConstructor()
            ->getMock();

        $repo = $this->getMockBuilder(ObjectRepository::class)
            ->setMethods(['findOneBy', 'find', 'findAll', 'findBy', 'getClassName'])
            ->disableOriginalConstructor()
            ->getMock();

        $repo->expects($this->once())
            ->method('findOneBy')
            ->willReturn(null);

        $em = $this->getMockBuilder(EntityManager::class)
            ->setMethods(['getRepository'])
            ->disableOriginalConstructor()
            ->getMock();

        $em->expects($this->once())
            ->method('getRepository')
            ->willReturn($repo);

        $logger = $logger = $this->getMockBuilder(Logger::class)
            ->setMethods(null)
            ->disableOriginalConstructor()
            ->getMock();

        $subscriber = new ProfilerDeathSubscriber(
            $tm,
            $logger,
            $em
        );

        $profiler = new Profiler(new TagCollection([
            new Tag('origin', 'unit.test')
        ]));

        $event = new ProfilerDeathEvent($profiler);

        $subscriber->process($event);
    }
}
