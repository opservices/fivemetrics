<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 24/02/17
 * Time: 13:04
 */

namespace GearmanBundle\Worker\Process;

use EssentialsBundle\Entity\EntityAbstract;

/**
 * Class Status
 * @package GearmanBundle\Worker\Process
 */
class Status extends EntityAbstract
{
    /**
     * The command string that was passed to Process
     * @see Process
     * @var string
     */
    protected $command;

    /**
     * Process ID
     * @var int
     */
    protected $pid;

    /**
     * TRUE if the process is still running, FALSE if it has terminated.
     * @var bool
     */
    protected $running;

    /**
     * TRUE if the child process has been terminated by an uncaught signal.
     * Always set to FALSE on Windows.
     * @var bool
     */
    protected $signaled;

    /**
     * TRUE if the child process has been stopped by a signal. Always set to FALSE on Windows.
     * @var bool
     */
    protected $stopped;

    /**
     * The exit code returned by the process (which is only meaningful if
     * running is FALSE). Only first call of this function return real value,
     * next calls return -1.
     * @var int
     */
    protected $exitCode;

    /**
     * The number of the signal that caused the child process to terminate its
     * execution (only meaningful if signaled is TRUE).
     * @var int
     */
    protected $termSig;

    /**
     * The number of the signal that caused the child process to stop its
     * execution (only meaningful if stopped is TRUE).
     * @var int
     */
    protected $stopSig;

    /**
     * @var bool
     */
    protected $undefined;

    /**
     * Status constructor.
     * @param string $command
     * @param int $pid
     * @param bool $running
     * @param bool $signaled
     * @param bool $stopped
     * @param bool $undefined
     * @param int $exitCode
     * @param int $termSig
     */
    public function __construct(
        string $command,
        int $pid,
        bool $running,
        bool $signaled,
        bool $stopped,
        bool $undefined,
        int $exitCode,
        int $termSig
    ) {
        $this->setCommand($command)
            ->setPid($pid)
            ->setRunning($running)
            ->setSignaled($signaled)
            ->setStopped($stopped)
            ->setUndefined($undefined)
            ->setExitCode($exitCode)
            ->setTermSig($termSig);
    }

    /**
     * @return bool
     */
    public function isUndefined(): bool
    {
        return $this->undefined;
    }

    /**
     * @param bool $undefined
     * @return Status
     */
    public function setUndefined(bool $undefined): Status
    {
        $this->undefined = $undefined;
        return $this;
    }

    /**
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }

    /**
     * @param string $command
     * @return Status
     */
    public function setCommand(string $command): Status
    {
        $this->command = $command;
        return $this;
    }

    /**
     * @return int
     */
    public function getPid(): int
    {
        return $this->pid;
    }

    /**
     * @param int $pid
     * @return Status
     */
    public function setPid(int $pid): Status
    {
        $this->pid = $pid;
        return $this;
    }

    /**
     * @return bool
     */
    public function isRunning(): bool
    {
        return $this->running;
    }

    /**
     * @param bool $running
     * @return Status
     */
    public function setRunning(bool $running): Status
    {
        $this->running = $running;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSignaled(): bool
    {
        return $this->signaled;
    }

    /**
     * @param bool $signaled
     * @return Status
     */
    public function setSignaled(bool $signaled): Status
    {
        $this->signaled = $signaled;
        return $this;
    }

    /**
     * @return bool
     */
    public function isStopped(): bool
    {
        return $this->stopped;
    }

    /**
     * @param bool $stopped
     * @return Status
     */
    public function setStopped(bool $stopped): Status
    {
        $this->stopped = $stopped;
        return $this;
    }

    /**
     * @return int
     */
    public function getExitCode(): int
    {
        return $this->exitCode;
    }

    /**
     * @param int $exitCode
     * @return Status
     */
    public function setExitCode(int $exitCode): Status
    {
        $this->exitCode = $exitCode;
        return $this;
    }

    /**
     * @return int
     */
    public function getTermSig(): int
    {
        return $this->termSig;
    }

    /**
     * @param int $termSig
     * @return Status
     */
    public function setTermSig(int $termSig): Status
    {
        $this->termSig = $termSig;
        return $this;
    }

    /**
     * @return int
     */
    public function getStopSig(): int
    {
        return $this->stopSig;
    }

    /**
     * @param int $stopSig
     * @return Status
     */
    public function setStopSig(int $stopSig): Status
    {
        $this->stopSig = $stopSig;
        return $this;
    }
}
