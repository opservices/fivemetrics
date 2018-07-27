<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 24/02/17
 * Time: 10:30
 */

namespace GearmanBundle\Worker\Manager;

/**
 * Gearman utilities class to manipulate workers
 */
use GearmanBundle\Collection\Worker\Manager\QueueCollection;
use GearmanBundle\Collection\Worker\Process\DescriptorCollection;
use GearmanBundle\Collection\Worker\Process\ProcessCollection;
use EssentialsBundle\Entity\Shell\Command\Argument;
use EssentialsBundle\Entity\Shell\Command\Command;
use GearmanBundle\Entity\Configuration\Worker;
use GearmanBundle\Worker\Process\Descriptor;
use GearmanBundle\Worker\Process\Process;

/**
 * Class Manager is a Gearman utilities class to manipulate workers
 * @package Gearman\Worker
 */
class Manager
{
    /**
     * @var string workersPath Is where are the workers' files.
     */
    const WORKERS_DIR = __DIR__ . '/../../Queue';

    /**
     * @var QueueCollection
     */
    protected $queues;

    /**
     * Manager constructor.
     * @param QueueCollection|null $queues
     */
    public function __construct(QueueCollection $queues = null)
    {
        $this->setQueues((is_null($queues)) ? new QueueCollection() : $queues);
    }

    /**
     * @return QueueCollection
     */
    public function getQueues(): QueueCollection
    {
        return $this->queues;
    }

    /**
     * @param QueueCollection $queues
     * @return Manager
     */
    public function setQueues(QueueCollection $queues): Manager
    {
        $this->queues = $queues;
        return $this;
    }

    /**
     * @param Queue $queue
     * @return Manager
     */
    public function addQueue(Queue $queue): Manager
    {
        $this->getQueues()->add($queue);
        return $this;
    }

    /**
     * @param Worker $conf
     * @return Process
     */
    protected function getProcessInstance(Worker $conf): Process
    {
        $descriptors = new DescriptorCollection();

        // stdin
        $descriptors->add(new Descriptor("r"));
        // stdout
        $descriptors->add(new Descriptor("a"));
        // stderr
        $descriptors->add(new Descriptor("a"));

        $command = new Command('/usr/local/bin/gworker-runner');
        $command->addArgument(new Argument('--class-name', $conf->getClass()));

        return new Process($descriptors, $command);
    }

    /**
     * The startWorkers method starts all workers with all their instances.
     * @return Manager
     */
    public function startWorkers(): Manager
    {
        $queues = $this->getQueues();

        foreach ($queues as $queue) {
            if ($this->hasWorkerRunning($queue)) {
                throw new \RuntimeException(
                    "There are one or more workers running yet." .
                    " Can't start a running worker."
                );
            }
        }

        foreach ($queues as $queue) {
            $desired = $queue->getConfiguration()->getDesired();

            for ($i=0; $i < $desired; $i++) {
                $queue->getProcesses()
                    ->add(
                        $this->getProcessInstance($queue->getConfiguration())
                    );
            }

            $processes = $queue->getProcesses();

            foreach ($processes as $process) {
                $process->start();
            }
        }

        return $this;
    }

    /**
     * This method kills all process of the all workers.
     *
     * @return Manager
     */
    public function stopWorkers(): Manager
    {
        $queues = $this->getQueues();

        foreach ($queues as $queue) {
            $processes = $queue->getProcesses();

            foreach ($processes as $process) {
                $process->stop();
            }

            $queue->setProcesses(new ProcessCollection());
        }

        return $this;
    }

    /**
     * Verify whether there is a instance runnig of the a worker.
     *
     * @param Queue $queue
     * @return Boolean
     */
    public function hasWorkerRunning(Queue $queue)
    {
        $processes = $queue->getProcesses();

        if (count($processes) <= 0) {
            return false;
        }

        foreach ($processes as $proc) {
            if ($proc->getStatus()->isRunning()) {
                return true;
            }
        }

        return false;
    }
}
