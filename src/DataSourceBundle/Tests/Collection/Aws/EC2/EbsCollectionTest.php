<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 02/03/17
 * Time: 21:47
 */

namespace DataSourceBundle\Tests\Collection\Aws\EC2;

use DataSourceBundle\Collection\Aws\EC2\EbsCollection;
use DataSourceBundle\Entity\Aws\EC2\Instance\Ebs;
use EssentialsBundle\Entity\DateTime\DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Class EbsCollectionTest
 * @package Test\Collection\Aws\EC2
 */
class EbsCollectionTest extends TestCase
{
    /**
     * @var EbsCollection
     */
    protected $ebsCollection;

    public function setUp()
    {
        $this->ebsCollection = new EbsCollection();
    }

    /**
     * @test
     */
    public function addEbs()
    {
        $this->ebsCollection->add(
            new Ebs(
                new DateTime(),
                false,
                'id',
                'attaching'
            )
        );

        $this->assertEquals(
            1,
            count($this->ebsCollection)
        );
    }
}
