<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 30/10/17
 * Time: 15:45
 */

namespace EssentialsBundle\Profiler;

use EssentialsBundle\Collection\Tag\TagCollection;
use EssentialsBundle\Entity\Account\AccountInterface;
use EssentialsBundle\Entity\EntityAbstract;
use EssentialsBundle\Entity\Tag\Tag;
use EssentialsBundle\Exception\Exceptions;
use EssentialsBundle\KernelLoader;
use EssentialsBundle\Profiler\Event\ProfilerDeathEvent;
use EssentialsBundle\Timer\Timer;

/**
 * Class Profiler
 * @package EssentialsBundle\Profiler
 */
class Profiler extends EntityAbstract
{
    /**
     * @var TagCollection
     */
    protected $tags;

    /**
     * @var bool
     */
    protected $events = false;

    /**
     * @var Timer
     */
    protected $timer;

    /**
     * Job constructor.
     * @param TagCollection $tags
     */
    public function __construct(
        TagCollection $tags,
        bool $events = false,
        Timer $timer = null
    ) {
        $this->setTags($tags);

        $this->events = $events;
        $this->timer = $timer ?? new Timer();
    }

    public function __destruct()
    {
        $this->timer->__destruct();
        if ($this->events === true) {
            $this->disableEvents();

            KernelLoader::load()
                ->getContainer()
                ->get('event_dispatcher')
                ->dispatch(
                    ProfilerDeathEvent::NAME,
                    new ProfilerDeathEvent($this)
                );
        }
    }

    public function __sleep()
    {
        if ($this->events) {
            $this->events = 'enabled';
        }

        return array_keys(get_object_vars($this));
    }

    public function __wakeup()
    {
        $this->events = ($this->events == 'enabled');
    }

    /**
     * @param AccountInterface $account
     * @param string $eventUid
     * @param string|null $parent
     * @param string $origin
     * @param bool $events
     * @return Profiler
     */
    public static function createFrom(
        AccountInterface $account,
        string $eventUid,
        string $parent = null,
        string $origin = 'collect',
        bool $events = false
    ): Profiler {
        $tags = new TagCollection([
            new Tag('account', $account->getUid()),
            new Tag('origin', $origin),
            new Tag('event', $eventUid),
        ]);

        (is_null($parent)) ?: $tags->add(new Tag('parent', $parent));

        return new static($tags, $events);
    }

    /**
     * @return bool
     */
    public function isEnabledEvents(): Bool
    {
        return $this->events;
    }

    /**
     * @return Profiler
     */
    public function enableEvents(): Profiler
    {
        $this->events = true;
        return $this;
    }

    /**
     * @return Profiler
     */
    public function disableEvents(): Profiler
    {
        $this->events = false;
        return $this;
    }

    /**
     * @param bool $creation
     * @return Profiler
     */
    public function resetTimers(bool $creation = false): Profiler
    {
        $this->timer->resetTimers($creation);
        return $this;
    }

    /**
     * @return float
     */
    public function getCreationTime(): float
    {
        return $this->timer->getStartTime();
    }

    /**
     * @return TagCollection
     */
    public function getTags(): TagCollection
    {
        return $this->tags;
    }

    /**
     * @param TagCollection $tags
     * @return Profiler
     */
    public function setTags(TagCollection $tags): Profiler
    {
        if (! $this->isValidTags($tags)) {
            throw new \InvalidArgumentException(
                'The origin tag must be defined to identify in which application layer this data was generated.',
                Exceptions::VALIDATION_ERROR
            );
        }

        $this->tags = $tags;

        return $this;
    }

    protected function isValidTags(TagCollection $tags): bool
    {
        return (!! $tags->find('origin'));
    }

    protected function round(float $number)
    {
        return round($number, 9);
    }

    /**
     * @return float
     */
    public function getLifetime(): float
    {
        return $this->timer->getTime();
    }

    /**
     * @return float|null
     */
    public function getProcessingTime()
    {
        return $this->timer->getTimeFromUnserialization();
    }

    /**
     * @return float
     */
    public function getWaitTime(): float
    {
        return $this->timer->getTimeUntilUnserialization();
    }

    /**
     * @return float|null
     */
    public function getQueueTime()
    {
        return $this->round(
            $this->timer->getTimeFromUnserialization() - $this->timer->getTimeFromSerialization()
        );
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'waitTime' => $this->getWaitTime(),
            'queueTime' => $this->getQueueTime(),
            'processingTime' => $this->getProcessingTime(),
            'lifeTime' => $this->getLifetime(),
        ];
    }
}
