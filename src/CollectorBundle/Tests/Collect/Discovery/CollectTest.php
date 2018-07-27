<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 26/09/17
 * Time: 08:23
 */

namespace CollectorBundle\Tests\Collect\Discovery;

use CollectorBundle\Collect\DataSource;
use CollectorBundle\Collect\Discovery\Collect as DiscoveryCollect;
use CollectorBundle\Collect\Parameter;
use CollectorBundle\Collect\ParameterCollection;
use DataSourceBundle\Entity\DataSource\DataSourceCollect;
use DataSourceBundle\Entity\DataSource\DataSource as DS;
use DataSourceBundle\Entity\DataSource\DataSourceParameter;
use DataSourceBundle\Entity\DataSource\DataSourceParameterValue;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class CollectTest extends TestCase
{
    /**
     * @test
     */
    public function equals()
    {
        $discoveryCollect = new DiscoveryCollect(
            new DataSource('aws.ec2', 5, 300),
            new ParameterCollection([
                new Parameter('aws.key', 'key-test'),
                new Parameter('aws.secret', 'secret-test'),
                new Parameter('aws.region', 'us-east-1'),
            ])
        );

        $ds = new DS('aws.ec2');
        $collect = new DataSourceCollect(null, $ds);

        $parameters = new ArrayCollection([
            new DataSourceParameterValue(
                $ds,
                null,
                new DataSourceParameter('aws.key'),
                null,
                'key-test'
            ),
            new DataSourceParameterValue(
                $ds,
                null,
                new DataSourceParameter('aws.secret'),
                null,
                'secret-test'
            ),
            new DataSourceParameterValue(
                $ds,
                null,
                new DataSourceParameter('aws.region'),
                null,
                'us-east-1'
            ),
        ]);

        $collect->setParameterValues($parameters);

        $this->assertTrue($discoveryCollect->equals($collect));
    }
}
