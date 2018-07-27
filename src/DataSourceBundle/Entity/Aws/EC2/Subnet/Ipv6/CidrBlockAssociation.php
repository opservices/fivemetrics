<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 13/02/17
 * Time: 14:44
 */

namespace DataSourceBundle\Entity\Aws\EC2\Subnet\Ipv6;

use DataSourceBundle\Entity\Aws\EntityAbstract;

/**
 * Class CidrBlockAssociation
 * @package DataSourceBundle\Entity\Aws\EC2\Subnet\Ipv6
 */
class CidrBlockAssociation extends EntityAbstract
{
    /**
     * @var string
     */
    protected $associationId;

    /**
     * @var string
     */
    protected $ipv6CidrBlock;

    /**
     * @var CidrBlockState
     */
    protected $ipv6CidrBlockState;

    /**
     * CidrBlockAssociation constructor.
     * @param string $associationId
     * @param string $ipv6CidrBlock
     * @param CidrBlockState $ipv6CidrBlockState
     */
    public function __construct(
        string $associationId,
        string $ipv6CidrBlock,
        CidrBlockState $ipv6CidrBlockState
    ) {
        $this->setAssociationId($associationId)
            ->setIpv6CidrBlock($ipv6CidrBlock)
            ->setIpv6CidrBlockState($ipv6CidrBlockState);
    }

    /**
     * @return string
     */
    public function getAssociationId(): string
    {
        return $this->associationId;
    }

    /**
     * @param string $associationId
     * @return CidrBlockAssociation
     */
    public function setAssociationId(string $associationId): CidrBlockAssociation
    {
        $this->associationId = $associationId;
        return $this;
    }

    /**
     * @return string
     */
    public function getIpv6CidrBlock(): string
    {
        return $this->ipv6CidrBlock;
    }

    /**
     * @param string $ipv6CidrBlock
     * @return CidrBlockAssociation
     */
    public function setIpv6CidrBlock(string $ipv6CidrBlock): CidrBlockAssociation
    {
        $this->ipv6CidrBlock = $ipv6CidrBlock;
        return $this;
    }

    /**
     * @return CidrBlockState
     */
    public function getIpv6CidrBlockState(): CidrBlockState
    {
        return $this->ipv6CidrBlockState;
    }

    /**
     * @param CidrBlockState $ipv6CidrBlockState
     * @return CidrBlockAssociation
     */
    public function setIpv6CidrBlockState(CidrBlockState $ipv6CidrBlockState): CidrBlockAssociation
    {
        $this->ipv6CidrBlockState = $ipv6CidrBlockState;
        return $this;
    }
}
