<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 07/12/17
 * Time: 15:05
 */

namespace EssentialsBundle\Tests\Profiler;

use EssentialsBundle\Collection\Tag\TagCollection;
use EssentialsBundle\Entity\Tag\Tag;
use EssentialsBundle\KernelLoader;
use EssentialsBundle\Profiler\Profiler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher;

class ProfilerTest extends TestCase
{
    /**
     * @test
     */
    public function disableAndEnableEvents()
    {
        $profiler = new Profiler(new TagCollection([ new Tag('origin', 'unit.test') ]));

        $profiler->disableEvents();
        $this->assertFalse($profiler->isEnabledEvents());
        $profiler->enableEvents();
        $this->assertTrue($profiler->isEnabledEvents());
        $profiler->disableEvents();
        $this->assertFalse($profiler->isEnabledEvents());
    }

    /**
     * @test
     */
    public function deathEvent()
    {
        $dispatcher = $this->getMockBuilder(ContainerAwareEventDispatcher::class)
            ->disableOriginalConstructor()
            ->setMethods(['dispatch'])
            ->getMock();

        $dispatcher->expects($this->once())
            ->method('dispatch')
            ->willReturn(true);

        $container = KernelLoader::load()->getContainer();

        $container->set('event_dispatcher', $dispatcher);

        $profiler = new Profiler(new TagCollection([ new Tag('origin', 'unit.test') ]));
        $profiler->enableEvents();
        unset($profiler);
    }

    /**
     * @test
     */
    public function getCreationTime()
    {
        $profiler = new Profiler(new TagCollection([ new Tag('origin', 'unit.test') ]));
        $this->assertGreaterThan(0, $profiler->getCreationTime());
    }

    /**
     * @test
     */
    public function resetTimers()
    {
        $profiler = new Profiler(new TagCollection([ new Tag('origin', 'unit.test') ]));

        $time = $profiler->getCreationTime();
        $profiler->resetTimers(true);

        usleep(100);

        $this->assertGreaterThan($time, $profiler->getCreationTime());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function createProfilerWithoutOriginTag()
    {
        new Profiler(new TagCollection());
    }
}
