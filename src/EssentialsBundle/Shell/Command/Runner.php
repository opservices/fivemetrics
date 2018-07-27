<?php

/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 28/01/17
 * Time: 19:40
 */

namespace EssentialsBundle\Shell\Command;

use EssentialsBundle\Entity\Shell\Command\Command;

/**
 * Class CommandRunner runs a shell command and keeps the stdout and stderr.
 * @package Shell
 */
class Runner
{
    const STD_IN  = 0;
    const STD_OUT = 1;
    const STD_ERR = 2;

    /**
     * @var string
     */
    protected $stdout;

    /**
     * @var string
     */
    protected $stderr;

    /**
     * @var int
     */
    protected $exitCode;

    /**
     * @var array
     */
    protected $pipes;

    /**
     * Returns the command stdout.
     * @return String
     */
    public function getStdout(): string
    {
        return $this->stdout;
    }

    /**
     * @param $stdout
     * @return Runner
     */
    protected function setStdout($stdout): Runner
    {
        $this->stdout = $stdout;
        return $this;
    }

    /**
     * Returns the command stderr.
     * @return String
     */
    public function getStderr(): string
    {
        return $this->stderr;
    }

    /**
     * @param null $stderr
     * @return Runner
     */
    protected function setStderr($stderr): Runner
    {
        $this->stderr = $stderr;
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
     * @param null $exitCode
     * @return Runner
     */
    protected function setExitCode($exitCode): Runner
    {
        $this->exitCode = $exitCode;
        return $this;
    }

    /**
     * @param array $pipes
     * @return Runner
     */
    protected function setPipes(array $pipes): Runner
    {
        $this->pipes = $pipes;
        return $this;
    }

    /**
     * @return array
     * @codeCoverageIgnore
     */
    protected function getDescriptors(): array
    {
        return [
            self::STD_IN  => ["pipe", "r"],
            self::STD_OUT => ["pipe", "w"],
            self::STD_ERR => ["pipe", "w"]
        ];
    }

    /**
     * @param string $command
     * @return Runner
     */
    protected function prepareToRun(string $command): Runner
    {
        if (empty($command)) {
            throw new \InvalidArgumentException(
                "Can't execute an empty command."
            );
        }

        $this->setStdout("")
            ->setStderr("")
            ->setExitCode(null)
            ->setPipes([]);

        return $this;
    }

    /**
     * @param string $command
     * @return resource
     * @codeCoverageIgnore
     */
    protected function runCommand(string $command)
    {
        return proc_open(
            $command,
            $this->getDescriptors(),
            $this->pipes
        );
    }

    /**
     * @param int $stream
     * @return string
     * @codeCoverageIgnore
     */
    protected function readOutput(
        int $stream = Runner::STD_OUT
    ): string {
        $this->switchStream($stream);

        return (empty($this->pipes[$stream]))
            ? ""
            : fread($this->pipes[$stream], 8192);
    }

    /**
     * Runs a shell command and returns the command exit code.
     * @param Command $command
     * @param Int $timeout
     * @return Runner
     * @throw InvalidArgumentException if $command is null or empty
     */
    public function run(Command $command, int $timeout = null): Runner
    {
        $commandHandle = $this->prepareToRun($command)
            ->runCommand($command);

        if (is_null($timeout)) {
            $this->setStdout($this->readOutput(self::STD_OUT))
                ->setStderr($this->readOutput(self::STD_ERR));
        } else {
            $timeout += time();
            $this->setStreamBlocking();
            $this->runWaitingUntil($commandHandle, $timeout, $command);
        }

        $this->setExitCode(proc_close($commandHandle));

        return $this;
    }

    /**
     * @param int $index
     * @return Runner
     * @codeCoverageIgnore
     */
    protected function switchStream(int $index): Runner
    {
        stream_select(
            $read = array($this->pipes[$index]),
            $write = null,
            $exceptions = null,
            0,
            10000
        );

        return $this;
    }

    /**
     * @return bool
     * @codeCoverageIgnore
     */
    protected function isCommandOver(): bool
    {
        return ((feof($this->pipes[self::STD_OUT]))
            && (feof($this->pipes[self::STD_ERR])));
    }

    /**
     * @return Runner
     * @codeCoverageIgnore
     */
    protected function setStreamBlocking(): Runner
    {
        stream_set_blocking($this->pipes[self::STD_OUT], 0);
        stream_set_blocking($this->pipes[self::STD_ERR], 0);

        return $this;
    }

    /**
     * @param resource $commandHandle
     * @param int $maxTime in seconds
     * @param string $command
     * @return bool
     */
    protected function runWaitingUntil(
        $commandHandle,
        int $maxTime,
        string $command
    ): bool {
        do {
            $timeLeft = $maxTime - time();

            $this->setStdout($this->getStdout() . $this->readOutput(self::STD_OUT))
                ->setStderr($this->getStderr() . $this->readOutput(self::STD_ERR));
        } while ((! $this->isCommandOver()) && ($timeLeft >= 0));

        if ($timeLeft < 0) {
            $this->endProcess($commandHandle);
            throw new \RuntimeException(
                "Command execution timeout on: " . $command
            );
        }

        return ($timeLeft < 0);
    }

    /**
     * @param $handle
     * @return bool
     * @codeCoverageIgnore
     */
    protected function endProcess($handle): bool
    {
        return (is_resource($handle))
            ? proc_terminate($handle)
            : true;
    }
}
