<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 22/02/17
 * Time: 13:24
 */

namespace DataSourceBundle\Tests\Aws\CloudWatch;

use DataSourceBundle\Aws\CloudWatch\CloudWatch;
use DataSourceBundle\Entity\Aws\Region\California;
use EssentialsBundle\Reflection;
use PHPUnit\Framework\TestCase;

/**
 * Class CloudWatchTest
 * @package DataSourceBundle\Test\Aws\CloudWatch
 */
class CloudWatchTest extends TestCase
{
    /**
     * @var CloudWatch
     */
    protected $cw;

    public function setUp()
    {
        $this->cw = new CloudWatch(
            'key',
            'secret',
            new California()
        );
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertInstanceOf(
            'Aws\CloudWatch\CloudWatchClient',
            $this->cw->getCloudWatchClient()
        );

        $this->assertEquals(
            'key',
            $this->cw->getCredential()->getAccessKeyId()
        );

        $this->assertEquals(
            'secret',
            $this->cw->getCredential()->getSecretKey()
        );

        $this->assertInstanceOf(
            'DataSourceBundle\Entity\Aws\Region\California',
            $this->cw->getRegion()
        );
    }

    /**
     * @test
     */
    public function listMetrics()
    {
        $cwClient = $this->getMockBuilder('Aws\CloudWatch\CloudWatchClient')
            ->disableOriginalConstructor();

        $cwClient = $cwClient->setMethods([ 'listMetrics' ])->getMock();

        $metrics = [
            'Metrics' => [
                [
                    "Namespace" => "AWS/EBS",
                    "MetricName" => "VolumeQueueLength",
                    "Dimensions" => [
                        [
                            "Name" => "VolumeId",
                            "Value" => "vol-0da1c90cd1ceefe14"
                        ]
                    ]
                ],
                [
                    "Namespace" => "AWS/EBS",
                    "MetricName" => "VolumeIdleTime",
                    "Dimensions" => [
                        [
                            "Name" => "VolumeId",
                            "Value" => "vol-0ea736dfaaf470315"
                        ]
                    ]
                ]
            ]
        ];

        $cwClient->expects($this->once())
            ->method('listMetrics')
            ->will($this->returnValue($metrics));

        Reflection::setPropertyOnObject($this->cw, 'cwCli', $cwClient);

        $this->assertEquals(
            $metrics['Metrics'],
            $this->cw->listMetrics()
        );
    }
}
