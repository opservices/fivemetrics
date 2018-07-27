<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 06/12/17
 * Time: 09:43
 */

namespace EssentialsBundle\Timer;

use EssentialsBundle\Entity\EntityAbstract;

class Timer extends EntityAbstract implements \Serializable
{
    /**
     * @var float
     */
    protected $start = null;

    /**
     * @var float
     */
    protected $serialized = null;

    /**
     * @var float
     */
    protected $unserialized = null;

    /**
     * @var float
     */
    protected $death = null;

    /**
     * @var float
     */
    protected $pausedTime = null;

    /**
     * Timer constructor.
     * @param float|null $start
     */
    public function __construct(float $start = null)
    {
        $this->start = (is_null($start))
            ? $this->getNow()
            : $start;
    }

    public function __destruct()
    {
        $this->death = $this->pause()
            ->getNow();
    }

    /**
     * @param bool $includeStart
     * @return Timer
     */
    public function resetTimers(bool $includeStart = false): Timer
    {
        if ($includeStart) {
            $this->start = $this->getNow();
        }

        $this->pausedTime = null;
        $this->serialized = null;
        $this->unserialized = null;
        $this->death = null;

        return $this;
    }

    /**
     * @return float
     */
    public function getStartTime(): float
    {
        return $this->start;
    }

    /**
     * @inheritDoc
     */
    public function serialize()
    {
        if (is_null($this->serialized)) {
            $this->serialized = $this->getNow();
        }

        return serialize($this->toArray());
    }

    /**
     * @inheritDoc
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);

        foreach ($data as $key => $value) {
            $this->$key = $data[$key];
        }

        $this->unserialized = $data['unserialized'] ?? $this->getNow();
    }

    /**
     * @return float
     */
    protected function getNow(): float
    {
        return (is_null($this->pausedTime))
            ? microtime(true)
            : $this->pausedTime;
    }

    /**
     * @return Timer
     */
    public function pause(): Timer
    {
        $this->pausedTime = $this->getNow();
        return $this;
    }

    /**
     * @return Timer
     */
    public function resume(): Timer
    {
        $this->pausedTime = null;
        return $this;
    }

    protected function round(float $number)
    {
        return round($number, 9);
    }

    /**
     * @return float
     */
    public function getTime(): float
    {
        return $this->round($this->getNow() - $this->start);
    }

    /**
     * @return float|null
     */
    public function getTimeFromUnserialization()
    {
        return (is_null($this->unserialized))
            ? null
            : $this->round($this->getNow() - $this->unserialized);
    }

    /**
     * @return float|null
     */
    public function getTimeUntilUnserialization()
    {
        return (is_null($this->unserialized))
            ? $this->getTime()
            : $this->round($this->unserialized - $this->start);
    }

    /**
     * @return float|null
     */
    public function getTimeFromSerialization()
    {
        return (is_null($this->serialized))
            ? null
            : $this->round($this->getNow() - $this->serialized);
    }

    /**
     * @return float|null
     */
    public function getTimeUntilSerialization()
    {
        return (is_null($this->serialized))
            ? null
            : $this->round($this->serialized - $this->start);
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        $properties = parent::jsonSerialize();

        $properties['start'] = $this->getStartTime();
        $properties['elapsedTime'] = $this->getTime();
        $properties['serialized'] = $this->serialized;
        $properties['unserialized'] = $this->unserialized;
        $properties['death'] = $this->death;
        $properties['timeUntilSerialization'] = $this->getTimeUntilSerialization();
        $properties['timeFromSerialization'] = $this->getTimeFromSerialization();
        $properties['timeUntilUnserialization'] = $this->getTimeUntilUnserialization();
        $properties['timeFromUnserialization'] = $this->getTimeFromUnserialization();

        return $properties;
    }
}
