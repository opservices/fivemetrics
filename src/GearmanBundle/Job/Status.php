<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 13/11/17
 * Time: 15:45
 */

namespace GearmanBundle\Job;

use EssentialsBundle\Entity\EntityAbstract;

/**
 * Class Status
 * @package GearmanBundle\Job
 */
class Status extends EntityAbstract
{
    const QUEUED = 'queued';

    const RUNNING = 'running';

    const UNKNOWN = 'unknown';

    const FINISHED = 'finished';

    /**
     * @var bool
     */
    protected $isKnown;

    /**
     * @var bool
     */
    protected $isRunning;

    /**
     * @var int
     */
    protected $numerator;

    /**
     * @var int
     */
    protected $denominator;

    /**
     * Status constructor.
     * @param array $stat
     */
    public function __construct(array $stat)
    {
        $this->isKnown     = !!$stat[0];
        $this->isRunning   = !!$stat[1];
        $this->numerator   = (int)$stat[2];
        $this->denominator = (int)$stat[3];
    }

    /**
     * @return bool
     */
    public function isKnown(): bool
    {
        return $this->isKnown;
    }

    /**
     * @return bool
     */
    public function isRunning(): bool
    {
        return $this->isRunning;
    }

    /**
     * @return bool
     */
    public function isWaiting(): bool
    {
        return ($this->isKnown() && ! $this->isRunning());
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        if ($this->isWaiting()) {
            return self::QUEUED;
        }

        if ($this->isRunning()) {
            return self::RUNNING;
        }

        return self::UNKNOWN;
    }

    /**
     * @return mixed
     */
    public function getNumerator(): int
    {
        return $this->numerator;
    }

    /**
     * @return mixed
     */
    public function getDenominator(): int
    {
        return $this->denominator;
    }

    /**
     * @return bool
     */
    public function equals(string $state): bool
    {
        return $this->getName() === $state;
    }

    /**
     * @return bool
     */
    public function isUnknown(): bool
    {
        return $this->equals(self::UNKNOWN);
    }
}
