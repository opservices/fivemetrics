<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 21/03/17
 * Time: 15:57
 */

namespace DataSourceBundle\Tests\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer;

use DataSourceBundle\Entity\Aws\Region\RegionProvider;
use EssentialsBundle\Entity\Account\Account;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer\DataLoader;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer\Job\Job;
use EssentialsBundle\Entity\DateTime\DateTime;
use PHPUnit\Framework\TestCase;
use EssentialsBundle\Reflection;

/**
 * Class DataLoaderTest
 * @package DataSourceBundle\Tests\Gearman\Queue\Collector\Generic\Processor\Aws\ElasticLoadBalancer
 */
class DataLoaderTest extends TestCase
{
    /**
     * @var DataLoader
     */
    protected $loader;

    public function setUp()
    {
        $account = new Account();
        $account->setUid('test');

        $this->loader = new DataLoader(new Job(
            $account,
            DateTime::createFromFormat(
                'Y-m-d H:i:s',
                '2017-08-07 14:10:00'
            ),
            RegionProvider::factory('us-east-1'),
            'key',
            'secret'
        ));
    }

    /**
     * @test
     */
    public function getEC2Client()
    {
        $client1 = Reflection::callMethodOnObject($this->loader, 'getEC2Client');
        $client2 = Reflection::callMethodOnObject($this->loader, 'getEC2Client');
        $this->assertSame($client1, $client2);
    }
}
