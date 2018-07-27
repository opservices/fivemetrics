<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 20/01/17
 * Time: 10:56
 */

namespace DataSourceBundle\Entity\Aws\EC2\Instance;

use DataSourceBundle\Entity\Aws\EntityAbstract;

/**
 * Class ProductCode
 * @package DataSourceBundle\Entity\Aws\EC2\Instance
 */
class ProductCode extends EntityAbstract
{
    const PRODUCT_CODE_TYPES = [
        'devpay',
        'marketplace'
    ];

    /**
     * @var string
     */
    protected $productCodeId;

    /**
     * @var string 'devpay|marketplace'
     */
    protected $productCodeType;

    /**
     * ProductCode constructor.
     * @param string $productCodeId
     * @param string $productCodeType
     */
    public function __construct(string $productCodeId, string $productCodeType)
    {
        $this->setProductCodeId($productCodeId)
            ->setProductCodeType($productCodeType);
    }

    /**
     * @return string
     */
    public function getProductCodeId(): string
    {
        return $this->productCodeId;
    }

    /**
     * @param string $productCodeId
     * @return ProductCode
     */
    public function setProductCodeId(string $productCodeId): ProductCode
    {
        $this->productCodeId = $productCodeId;
        return $this;
    }

    /**
     * @return string
     */
    public function getProductCodeType(): string
    {
        return $this->productCodeType;
    }

    /**
     * @param $productCodeType
     * @return ProductCode
     */
    public function setProductCodeType(string $productCodeType): ProductCode
    {
        if (! in_array($productCodeType, self::PRODUCT_CODE_TYPES)) {
            throw new \InvalidArgumentException(
                'An invalid product code type was provided:' .
                ' "' . $productCodeType .'"'
            );
        }

        $this->productCodeType = $productCodeType;
        return $this;
    }
}
