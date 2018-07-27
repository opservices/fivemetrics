<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 28/01/17
 * Time: 23:04
 */

namespace EssentialsBundle\Entity\Shell\Command;

use EssentialsBundle\Collection\Shell\Command\ArgumentCollection;
use EssentialsBundle\Entity\EntityAbstract;

/**
 * Class Command
 * @package Entity\Shell\Command
 */
class Command extends EntityAbstract
{
    /**
     * @var string
     */
    protected $executable;

    /**
     * @var ArgumentCollection
     */
    protected $arguments;

    public function __construct(
        string $executable,
        ArgumentCollection $arguments = null
    ) {
        $this->setExecutable($executable)
            ->setArguments(
                (is_null($arguments)) ? new ArgumentCollection() : $arguments
            );
    }

    /**
     * @return string
     */
    public function getExecutable(): string
    {
        return $this->executable;
    }

    /**
     * @param string $executable
     * @return Command
     */
    public function setExecutable(string $executable): Command
    {
        $this->executable = $this->escapeCommand($executable);
        return $this;
    }

    /**
     * @param string $cmd
     * @return string
     */
    public static function escapeCommand(string $cmd): string
    {
        return escapeshellcmd($cmd);
    }

    /**
     * @return ArgumentCollection
     */
    public function getArguments(): ArgumentCollection
    {
        return $this->arguments;
    }

    /**
     * @param ArgumentCollection $arguments
     * @return Command
     */
    public function setArguments(ArgumentCollection $arguments): Command
    {
        $this->arguments = $arguments;
        return $this;
    }

    /**
     * @param Argument $argument
     * @return Command
     */
    public function addArgument(Argument $argument): Command
    {
        $this->arguments->add($argument);
        return $this;
    }

    public function __clone()
    {
        $this->arguments = clone $this->arguments;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getExecutable() . $this->getArguments();
    }
}
