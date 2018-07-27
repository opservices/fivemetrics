<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 27/11/17
 * Time: 08:32
 */

namespace CollectorBundle\Tests\Collector;

use CollectorBundle\Collect\Collect;
use CollectorBundle\Collect\CollectBucket;
use CollectorBundle\Collect\CollectCollection;
use CollectorBundle\Collect\DataSource;
use CollectorBundle\Collect\Parameter;
use CollectorBundle\Collect\ParameterCollection;
use CollectorBundle\Collector\ResultSender;
use Doctrine\Common\Cache\PredisCache;
use EssentialsBundle\Cache\CacheFactory;
use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Entity\Builder\EntityBuilderProvider;
use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\MutexProvider\MutexProvider;
use GearmanBundle\TaskManager\TaskManager;
use malkusch\lock\exception\LockAcquireException;
use malkusch\lock\exception\LockReleaseException;
use malkusch\lock\mutex\PredisMutex;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Monolog\Logger;

class ResultSenderTest extends TestCase
{
    /**
     * @param CollectBucket $bucket
     * @testdox
     */
    public function send()
    {
        $bucket = $this->createBucket();
        list($mutexProvider, $cacheFactory, $logger, $tm) = $this->mockDependencies($bucket);
        $sender = new ResultSender($logger, $tm, $cacheFactory, $mutexProvider);
        $sender->send($bucket);
    }

    /**
     * @param CollectBucket $bucket
     * @return array
     */
    protected function mockDependencies(CollectBucket $bucket): array
    {
        $mutex = $this->getMockBuilder(PredisMutex::class)
            ->disableOriginalConstructor()
            ->setMethods(['synchronized'])
            ->getMock();

        $mutex->expects($this->any())
            ->method('synchronized')
            ->willReturn(true);

        $mutexProvider = $this->getMockBuilder(MutexProvider::class)
            ->disableOriginalConstructor()
            ->setMethods(['getMutexInstance'])
            ->getMock();

        $mutexProvider->expects($this->any())
            ->method('getMutexInstance')
            ->willReturn($mutex);

        $cacheProvider = $this->getMockBuilder(PredisCache::class)
            ->disableOriginalConstructor()
            ->setMethods(['save'])
            ->getMock();

        $cacheProvider->expects($this->any())
            ->method('save')
            ->willReturn(true);

        $cacheFactory = $this->getMockBuilder(CacheFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['factory'])
            ->getMock();

        $cacheFactory->expects($this->any())
            ->method('factory')
            ->willReturn($cacheProvider);

        $logger = $this->getMockBuilder(Logger::class)
            ->disableOriginalConstructor()
            ->setMethods(['log'])
            ->getMock();

        $logger->expects($this->any())
            ->method('log')
            ->willReturn(true);

        $tm = $this->getMockBuilder(TaskManager::class)
            ->disableOriginalConstructor()
            ->setMethods(['runBackground'])
            ->getMock();

        $tm->expects($this->exactly(count($bucket->getCollects())))
            ->method('runBackground')
            ->willReturn(true);

        return array($mutexProvider, $cacheFactory, $logger, $tm);
    }

    /**
     * @return CollectBucket
     */
    protected function createBucket(): CollectBucket
    {
        $collect = new Collect(
            1,
            new DataSource('aws.ec2', 5, 300),
            new ParameterCollection([
                new Parameter('aws.key', 'key-test'),
                new Parameter('aws.secret', 'secret-test'),
                new Parameter('aws.region', 'us-east-1'),
            ])
        );

        $account = new Account();
        $account->setEmail('tester@fivemetrics.io')
            ->setUid('tester')
            ->setRoles(['ROLE_USER'])
        ;

        $bucket = new CollectBucket(
            $account,
            new DateTime(),
            new CollectCollection([$collect])
        );
        return $bucket;
    }
}
