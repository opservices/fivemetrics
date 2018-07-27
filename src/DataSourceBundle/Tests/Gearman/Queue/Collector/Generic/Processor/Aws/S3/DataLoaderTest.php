<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 7/13/17
 * Time: 10:42 AM
 */

namespace DataSourceBundle\Tests\Gearman\Queue\Collector\Generic\Processor\Aws\S3;

use DataSourceBundle\Entity\Aws\S3\Region\RegionProvider;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\S3\DataLoader;
use DataSourceBundle\Gearman\Queue\Collector\Generic\Processor\Aws\S3\Job\Job;
use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\Reflection;
use PHPUnit\Framework\TestCase;

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
    public function getS3Client()
    {
        $client1 = Reflection::callMethodOnObject($this->loader, 'getS3Client');
        $client2 = Reflection::callMethodOnObject($this->loader, 'getS3Client');
        $this->assertSame($client1, $client2);
    }
}