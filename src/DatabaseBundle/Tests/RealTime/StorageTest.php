<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 24/04/18
 * Time: 17:03
 */

namespace DatabaseBundle\Tests\RealTime;

use DatabaseBundle\RealTime\Storage;
use Doctrine\Common\Cache\PredisCache;
use EssentialsBundle\Collection\Metric\RealTimeDataCollection;
use EssentialsBundle\Entity\Metric\RealTimeData;
use EssentialsBundle\Reflection;
use PHPUnit\Framework\TestCase;

class StorageTest extends TestCase
{
    /**
     * @test
     */
    public function filterValidKeys()
    {
        $cache = $this->createCacheMock(['fetch']);
        $cache->method('fetch')->willReturn(true);
        $keys = ['unit.test' => ['a', 'b', 'c']];
        $storage = new Storage($cache);

        $result = Reflection::callMethodOnObject(
            $storage,
            'filterValidKeys',
            [$keys['unit.test'], 'unit.test']
        );

        $this->assertEquals($keys, $result);
    }

    /**
     * @test
     */
    public function filterValidKeysWithoutCachedData()
    {
        $cache = $this->createCacheMock(['fetch']);
        $cache->method('fetch')->willReturn(false);
        $keys = ['unit.test' => ['a', 'b', 'c']];
        $storage = new Storage($cache);

        $result = Reflection::callMethodOnObject(
            $storage,
            'filterValidKeys',
            [$keys['unit.test'], 'unit.test']
        );

        $this->assertEquals([], $result);
    }

    /**
     * @test
     */
    public function persistMixedRealTimeData()
    {
        $cache = $this->createCacheMock(['save']);
        $cache->method('save')->willReturn(true);
        $storage = new Storage($cache);

        $collection = new RealTimeDataCollection([
            new RealTimeData('unit.test', '', 'case'),
            new RealTimeData('unit.test', '', 'case2'),
            new RealTimeData('unit.test', ''),
            new RealTimeData('unit.test2', '', 'case2'),
        ]);

        foreach ($collection as $realTime) {
            $storage->persist($realTime);
        }

        $referenceKeys = Reflection::getPropertyOnObject($storage, 'referenceKeys');

        $this->assertEquals([
            'realtime-unit.test' => [
                'realtime-unit.test-case',
                'realtime-unit.test-case2',
            ],
            'realtime-unit.test2' => [
                'realtime-unit.test2-case2',
            ],
        ], $referenceKeys);
    }

    /**
     * @testdox Should filter results based on a given filter
     *
     * @dataProvider filtersProvider
     */
    public function fetchByFilter($expected, $filter)
    {
        $storage = new Storage($this->createCacheMock());
        $data = [
            'us-east-1' => [
                ['State' => 'active', 'Used' => 1],
                ['State' => 'active', 'Used' => 0],
                ['State' => 'payment-failed'],
                ['State' => 'payment-pending'],
                ['State' => 'retired'],
            ]
        ];

        $result = Reflection::callMethodOnObject($storage, 'fetchByFilter', [$data, $filter]);
        $this->assertCount($expected, $result['us-east-1']);
    }

    public function filtersProvider()
    {
        return [
            "Should return 2 item for active state filter" => [2, ["state" => ["active"]]],
            "Should return 1 item for payment-pending state filter" => [1, ["state" => ["payment-pending"]]],
            "Should return 1 item for payment-failed state filter" => [1, ["state" => ["payment-failed"]]],
            "Should return 1 item for retired state filter" => [1, ["state" => ["retired"]]],
            "Should return 3 items for retired or active state filter" => [3, ["state" => ["retired", "active"]]],
            "Should return 0 item for retired or active state filter" => [0, ["invalid" => ["retired", "active"]]],
            "Should return 5 items for retired and active state filter" => [5, []],
            "Should return 1 item for an active state and used filter" => [1, ['state' => ['active'], 'used' => ["1"]]],
            "Should return 1 item for an active state and used filter" => [1, ['state' => ['active'], 'used' => ["0"]]],
        ];
    }

    /**
     * @testdox Should always return an array
     *
     * @dataProvider fetchProvider
     */
    public function fetchReferencesKeys($expected, $data)
    {
        $cache = $this->createCacheMock();
        $cache->method('fetch')->willReturn($data);
        $storage = new Storage($cache);
        $actual = Reflection::callMethodOnObject($storage, 'fetchReferencesKeys', ['bla']);
        $this->assertCount($expected, $actual);
    }

    public function fetchProvider()
    {
        return [
          [0, false],
          [0, true],
          [0, ""],
          [0, 'fla'],
          [0, []],
          [1, [1]],
          [0, null],
        ];
    }

    /**
     * @param $methods
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createCacheMock($methods = []): \PHPUnit_Framework_MockObject_MockObject
    {
        return empty($methods)
            ? $this->createMock(PredisCache::class)
            : $this->getMockBuilder(PredisCache::class)
                ->disableOriginalConstructor()
                ->setMethods($methods)
                ->getMock();
    }
}
