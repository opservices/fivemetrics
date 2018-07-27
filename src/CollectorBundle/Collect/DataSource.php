<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 25/09/17
 * Time: 14:55
 */

namespace CollectorBundle\Collect;

use EssentialsBundle\Entity\EntityAbstract;

class DataSource extends EntityAbstract
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $maxConcurrency;

    /**
     * @var int
     */
    protected $collectInterval;

    /**
     * DataSource constructor.
     * @param string $name
     * @param int $maxConcurrency
     */
    public function __construct(
        string $name,
        int $maxConcurrency,
        int $collectInterval
    ) {
        $this->setName($name)
            ->setMaxConcurrency($maxConcurrency)
            ->setCollectInterval($collectInterval);
    }

    /**
     * @return int
     */
    public function getCollectInterval(): int
    {
        return $this->collectInterval;
    }

    /**
     * @param int $collectInterval
     */
    public function setCollectInterval(int $collectInterval): void
    {
        $this->collectInterval = $collectInterval;
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
     * @return DataSource
     */
    public function setName(string $name): DataSource
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getMaxConcurrency(): int
    {
        return $this->maxConcurrency;
    }

    /**
     * @param int $maxConcurrency
     * @return DataSource
     */
    public function setMaxConcurrency(int $maxConcurrency): DataSource
    {
        $this->maxConcurrency = $maxConcurrency;
        return $this;
    }
}
