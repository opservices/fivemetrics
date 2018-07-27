<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 16/10/17
 * Time: 17:15
 */

namespace EssentialsBundle\Gearman\Queue;

use EssentialsBundle\Helpers\MailHelper;
use EssentialsBundle\KernelLoader;
use GearmanBundle\Collection\Worker\QueueCollection;
use GearmanBundle\Worker\Queue;
use GearmanBundle\Worker\WorkerAbstract;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MailSender extends WorkerAbstract
{
    protected $container;

    protected $mailHelper;

    public function __construct(
        $jobServers = null,
        $worker = null,
        $configuration = null,
        $errorDispatcher = null,
        ContainerInterface $container = null
    )
    {
        parent::__construct($jobServers, $worker, $configuration, $errorDispatcher);
        $this->loadContainer($container);
    }

    protected function loadContainer(ContainerInterface $container = null)
    {
        $this->container = $container ?? KernelLoader::load()->getContainer();
    }

    /**
     * @return MailHelper
     */
    public function getMailHelper(): MailHelper
    {
        return $this->mailHelper ?? $this->container->get(MailHelper::class);
    }

    protected function getQueues(): QueueCollection
    {
        return new QueueCollection([
            new Queue('mail-sender', 'process')
        ]);
    }

    protected function getConfiguration($key, $default = null)
    {
        return (isset($this->configuration[$key]))
            ? $this->configuration[$key]
            : $default;
    }

    /**
     * @param \GearmanJob $job
     */
    public function process(\GearmanJob $job)
    {
        /** @var \Swift_Message $message */
        $message = unserialize($job->workload());
        if (!is_a($message, \Swift_Message::class)) {
            $this->errorDispatcher->send(
                __CLASS__ . ': An invalid message has been provided.'
            );
            return;
        }

        $retries = $this->getConfiguration('retries', 5);
        $waitTime = $this->getConfiguration('waitTime', 5);
        $sent = false;

        while ((! $sent) && ($retries > 0)) {
            try {
                $sent = $this->getMailHelper()->sendMessage($message);
            } catch (\Swift_TransportException $e) {
                $this->errorDispatcher->send(
                    __CLASS__ . ': ' . $e->getMessage(),
                    null,
                    Logger::WARNING
                );

                KernelLoader::reload();
                $this->loadContainer();

                $retries--;
                sleep($waitTime);
            } catch (\Throwable $e) {
                $this->errorDispatcher->send($e);
                break;
            }
        }

        if (! $sent) {
            $this->errorDispatcher->send(sprintf(
                "Couldn't send the e-mail to account: $s",
                implode(",", $message->getTo())
            ));
        }
    }
}
