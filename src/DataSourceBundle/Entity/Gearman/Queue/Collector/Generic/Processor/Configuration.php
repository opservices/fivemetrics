<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 09/03/17
 * Time: 18:36
 */

namespace DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor;

use EssentialsBundle\Entity\EntityAbstract;

/**
 * Class Configuration
 * @package DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor
 */
class Configuration extends EntityAbstract
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $maxRetries;

    /**
     * Configuration constructor.
     * @param string $name
     * @param int $maxRetries
     */
    public function __construct(string $name, int $maxRetries)
    {
        $this->setName($name)
            ->setMaxRetries($maxRetries);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Configuration
     */
    public function setName(string $name): Configuration
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxRetries(): int
    {
        return $this->maxRetries;
    }

    /**
     * @param int $maxRetries
     * @return Configuration
     */
    public function setMaxRetries(int $maxRetries): Configuration
    {
        $this->maxRetries = $maxRetries;
        return $this;
    }
}
