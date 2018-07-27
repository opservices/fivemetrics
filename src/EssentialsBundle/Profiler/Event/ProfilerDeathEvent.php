<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 01/12/17
 * Time: 08:55
 */

namespace EssentialsBundle\Profiler\Event;

use EssentialsBundle\Profiler\Profiler;
use Symfony\Component\EventDispatcher\Event;

class ProfilerDeathEvent extends Event
{
    const NAME = 'profiler.death';

    /**
     * @var Profiler
     */
    protected $profiler;

    /**
     * JobDeathEvent constructor.
     * @param Profiler $job
     */
    public function __construct(Profiler $profiler)
    {
        $this->profiler = $profiler;
    }

    /**
     * @return Profiler
     */
    public function getProfiler(): Profiler
    {
        return $this->profiler;
    }
}
