<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 8/3/17
 * Time: 3:32 PM
 */

namespace DataSourceBundle\Tests\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier;

use DataSourceBundle\Entity\Aws\Glacier\Region\RegionProvider;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier\DataLoader;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier\Job\Job;
use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\Reflection;
use PHPUnit\Framework\TestCase;

/**
 * Class DataLoaderTest
 * @package DataSourceBundle\Tests\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier
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
        $client1 = Reflection::callMethodOnObject($this->loader, 'getGlacierClient');
        $client2 = Reflection::callMethodOnObject($this->loader, 'getGlacierClient');
        $this->assertSame($client1, $client2);
    }
}
