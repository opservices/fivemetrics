<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 17/02/17
 * Time: 14:10
 */

namespace DataSourceBundle\Tests\Entity\Aws\EC2\Instance;

use DataSourceBundle\Entity\Aws\EC2\Instance\ProductCode;
use PHPUnit\Framework\TestCase;

/**
 * Class ProductCodeTest
 * @package DataSourceBundle\Tests\Entity\Aws\EC2\Instance
 */
class ProductCodeTest extends TestCase
{
    /**
     * @var ProductCode
     */
    protected $pCode;

    public function setUp()
    {
        $this->pCode = new ProductCode(
            'productCodeId',
            'devpay'
        );
    }

    /**
     * @test
     */
    public function getConstructorParameters()
    {
        $this->assertEquals(
            'productCodeId',
            $this->pCode->getProductCodeId()
        );

        $this->assertEquals(
            'devpay',
            $this->pCode->getProductCodeType()
        );
    }

    /**
     * @test
     */
    public function setProductCodeId()
    {
        $this->pCode->setProductCodeId('productCodeId.test');

        $this->assertEquals(
            'productCodeId.test',
            $this->pCode->getProductCodeId()
        );
    }

    /**
     * @test
     */
    public function setProductCodeType()
    {
        $this->pCode->setProductCodeType('marketplace');

        $this->assertEquals(
            'marketplace',
            $this->pCode->getProductCodeType()
        );
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function setInvalidProductCodeType()
    {
        $this->pCode->setProductCodeType('test');
    }
}
