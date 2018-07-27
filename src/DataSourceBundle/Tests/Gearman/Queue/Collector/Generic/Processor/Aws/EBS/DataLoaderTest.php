<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 8/16/17
 * Time: 3:28 PM
 */

namespace DataSourceBundle\Tests\Gearman\Queue\Collector\Generic\Processor\Aws\EBS;

use DataSourceBundle\Entity\Aws\EC2\Region\RegionProvider;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EBS\DataLoader;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\EBS\Job\Job;
use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\Reflection;
use PHPUnit\Framework\TestCase;

/**
 * Class DataLoaderTest
 * @package DataSourceBundle\Tests\Gearman\Queue\Collector\Generic\Processor\Aws\EBS
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
