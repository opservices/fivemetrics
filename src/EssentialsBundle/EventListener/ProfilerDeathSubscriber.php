<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 01/12/17
 * Time: 08:57
 */

namespace EssentialsBundle\EventListener;

use Doctrine\ORM\EntityManagerInterface;
use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Entity\Account\AccountInterface;
use EssentialsBundle\Exception\Exceptions;
use EssentialsBundle\Profiler\Event\ProfilerDeathEvent;
use GearmanBundle\Job\Job;
use GearmanBundle\TaskManager\TaskManager;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProfilerDeathSubscriber implements EventSubscriberInterface
{
    /**
     * @var TaskManager
     */
    protected $tm;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var AccountInterface
     */
    protected $account;

    /**
     * ProfilerDeathSubscriber constructor.
     * @param TaskManager $tm
     * @param LoggerInterface $logger
     * @param EntityManagerInterface $em
     * @param AccountInterface|null $account
     */
    public function __construct(
        TaskManager $tm,
        LoggerInterface $logger,
        EntityManagerInterface $em,
        AccountInterface $account = null
    ) {
        $this->tm = $tm;
        $this->logger = $logger;
        $this->em = $em;
        $this->account = $account ?? $this->getSystemAccountInstance();
    }

    /**
     * @return AccountInterface
     */
    protected function getSystemAccountInstance(): AccountInterface
    {
        $account = $this->account ?? $this->account = $this->em
            ->getRepository(Account::class)
            ->findOneBy([ 'uid' => 'system' ]);

        if (empty($account)) {
            throw new \RuntimeException(
                "Couldn't load the system account from DB.",
                Exceptions::RUNTIME_ERROR
            );
        }

        return $account;
    }

    public static function getSubscribedEvents()
    {
        return [
            ProfilerDeathEvent::NAME => 'process',
        ];
    }

    public function process(ProfilerDeathEvent $event)
    {
        $this->tm->getClient()->addTaskBackground(
            'profiling',
            serialize(new Job(
                $this->account,
                null,
                $event->getProfiler()
            ))
        );

        if (! $this->tm->getClient()->runTasks()) {
            $this->logger->log(
                Logger::ERROR,
                $this->tm->getClient()->error()
            );
        }
    }
}
