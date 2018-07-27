<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 22/02/17
 * Time: 10:03
 */

namespace DataSourceBundle\Tests\Aws;

use Aws\Credentials\Credentials;
use DataSourceBundle\Aws\ClientAbstract;
use DataSourceBundle\Entity\Aws\Region\California;
use DataSourceBundle\Entity\Aws\Region\SaoPaulo;
use PHPUnit\Framework\TestCase;

/**
 * Class ClientAbstractTest
 * @package DataSourceBundle\Test\Aws
 */
class ClientAbstractTest extends TestCase
{
    /**
     * @var Client
     */
    protected $client;

    public function setUp()
    {
        $this->client = new class ('key', 'secret', new California()) extends ClientAbstract
        {
            public function checkCredential(): bool
            {
                return true;
            }
        };
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals(
            'key',
            $this->client->getCredential()->getAccessKeyId()
        );

        $this->assertEquals(
            'secret',
            $this->client->getCredential()->getSecretKey()
        );

        $this->assertInstanceOf(
            'DataSourceBundle\Entity\Aws\Region\California',
            $this->client->getRegion()
        );
    }

    /**
     * @test
     */
    public function setRegion()
    {
        $this->client->setRegion(new SaoPaulo());

        $this->assertInstanceOf(
            'DataSourceBundle\Entity\Aws\Region\SaoPaulo',
            $this->client->getRegion()
        );
    }

    /**
     * @test
     */
    public function setCredential()
    {
        $credential = new Credentials('key.test', 'secret.test');

        $this->client->setCredential($credential);

        $this->assertEquals(
            $credential,
            $this->client->getCredential()
        );
    }
}
