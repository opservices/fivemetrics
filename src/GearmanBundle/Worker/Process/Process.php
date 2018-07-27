<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 24/02/17
 * Time: 11:09
 */

namespace GearmanBundle\Worker\Process;

use GearmanBundle\Collection\Worker\Process\DescriptorCollection;
use EssentialsBundle\Entity\EntityAbstract;
use EssentialsBundle\Entity\Shell\Command\Command;
use EssentialsBundle\FunctionCaller;

/**
 * Class Process
 * @package Gearman\Worker
 */
class Process extends EntityAbstract
{
    /**
     * @var DescriptorCollection
     */
    protected $descriptors;

    /**
     * @var array
     */
    protected $pipes = [];

    /**
     * @var resource
     */
    protected $handler = null;

    /**
     * @var Command
     */
    protected $command;

    /**
     * @var Status
     */
    protected $status;

    /**
     * @var FunctionCaller
     */
    protected $fnCaller;

    /**
     * Process constructor.
     * @param DescriptorCollection $descriptors
     * The descriptors will be used on this sequence:
     *   0 - stdin
     *   1 - stdout
     *   2 - stderr
     * @param Command $command
     */
    public function __construct(
        DescriptorCollection $descriptors,
        Command $command
    ) {
        $this->setDescriptors($descriptors)
            ->setCommand($command);

        $this->fnCaller = new FunctionCaller();
    }

    /**
     * @return mixed
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @param mixed $command
     * @return Process
     */
    public function setCommand($command): Process
    {
        $this->command = $command;
        return $this;
    }

    /**
     * @return DescriptorCollection
     */
    public function getDescriptors(): DescriptorCollection
    {
        return $this->descriptors;
    }

    /**
     * @param DescriptorCollection $descriptors
     * The descriptors will be used on this sequence:
     *   0 - stdin
     *   1 - stdout
     *   2 - stderr
     * @return Process
     */
    public function setDescriptors(DescriptorCollection $descriptors): Process
    {
        $this->descriptors = $descriptors;
        return $this;
    }

    /**
     * @return array
     */
    public function getPipes(): array
    {
        return $this->pipes;
    }

    /**
     * @param array $pipes
     * @return Process
     */
    public function setPipes(array $pipes): Process
    {
        $this->pipes = $pipes;
        return $this;
    }

    /**
     * @return resource
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * @param resource $handler
     * @return Process
     */
    public function setHandler($handler): Process
    {
        $this->handler = $handler;
        return $this;
    }

    /**
     * @return Process
     */
    public function start(): Process
    {
        $handler = $this->fnCaller->proc_open(
            "exec php " . $this->getCommand(),
            $this->getDescriptors()->toArray(),
            $this->getPipes()
        );

        $this->setHandler($handler);

        return $this;
    }

    /**
     * @return Status
     */
    public function getStatus(): Status
    {
        $arr = @$this->fnCaller->proc_get_status($this->getHandler());
        ($arr) ?: $arr = [
            'command'   => $this->getCommand(),
            'pid'       => 0,
            'running'   => false,
            'signaled'  => false,
            'stopped'   => false,
            'exitcode'  => -1,
            'termsig'   => -1,
            'stopsig'   => -1,
            'undefined' => true
        ];

        return new Status(
            $arr['command'],
            $arr['pid'],
            $arr['running'],
            $arr['signaled'],
            $arr['stopped'],
            !!$arr['undefined'],
            $arr['exitcode'],
            $arr['termsig'],
            $arr['stopsig']
        );
    }

    /**
     * Kills the process opened by "Process->start()"
     *
     * @param Integer $signal This optional parameter is only useful on POSIX
     * operating systems; you may specify a signal to send to the process using
     * the kill(2) system call. The default is SIGTERM.
     * @return bool Returns the termination status of the process that was run.
     **/
    public function stop($signal = SIGTERM): bool
    {
        $status = @$this->fnCaller->proc_terminate($this->getHandler(), $signal);

        if ($status) {
            return $status;
        }

        $status = $this->getStatus();

        return ($status->isUndefined())
            ?: !!@$this->fnCaller->posix_kill($status->getPid(), $signal);
    }
}
