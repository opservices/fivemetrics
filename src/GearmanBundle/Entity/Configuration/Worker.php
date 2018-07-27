<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 23/02/17
 * Time: 08:34
 */

namespace GearmanBundle\Entity\Configuration;

use EssentialsBundle\Entity\EntityAbstract;

/**
 * Class Worker
 * @package GearmanBundle\Entity\Configuration
 */
class Worker extends EntityAbstract
{
    /**
     * @var string
     */
    protected $class;

    /**
     * @var int
     */
    protected $desired;

    protected $configuration;

    /**
     * Worker constructor.
     * @param string $class
     * @param int $desired
     * @param null $configuration
     */
    public function __construct(
        string $class,
        int $desired,
        $configuration = null
    ) {
        $this->setDesired($desired)
            ->setClass($class)
            ->setConfiguration($configuration);
    }

    /**
     * @return mixed
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @param mixed $configuration
     * @return Worker
     */
    public function setConfiguration($configuration): Worker
    {
        $this->configuration = $configuration;
        return $this;
    }

    /**
     * @return string
     */
    public function getClass(): string
    {
        return $this->class;
    }

    /**
     * @param string $class
     * @return Worker
     */
    public function setClass(string $class): Worker
    {
        $this->class = $class;
        return $this;
    }

    /**
     * @return int
     */
    public function getDesired(): int
    {
        return $this->desired;
    }

    /**
     * @param int $desired
     * @return Worker
     */
    public function setDesired(int $desired): Worker
    {
        $this->desired = $desired;
        return $this;
    }
}
