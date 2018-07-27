<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 13/02/17
 * Time: 14:45
 */

namespace DataSourceBundle\Entity\Aws\EC2\Subnet\Ipv6;

use DataSourceBundle\Entity\Aws\EntityAbstract;

/**
 * Class CidrBlockState
 * @package DataSourceBundle\Entity\Aws\EC2\Subnet\Ipv6
 */
class CidrBlockState extends EntityAbstract
{
    const STATE_TYPES = [
        'associating',
        'associated',
        'disassociating',
        'disassociated',
        'failing',
        'failed'
    ];

    /**
     * @var string
     */
    protected $state;

    /**
     * @var string
     */
    protected $statusMessage;

    /**
     * CidrBlockState constructor.
     * @param string $state
     * @param string $statusMessage
     */
    public function __construct(string $state, string $statusMessage)
    {
        $this->setState($state)
            ->setStatusMessage($statusMessage);
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @param string $state
     * @return CidrBlockState
     */
    public function setState(string $state): CidrBlockState
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatusMessage(): string
    {
        return $this->statusMessage;
    }

    /**
     * @param string $statusMessage
     * @return CidrBlockState
     */
    public function setStatusMessage(string $statusMessage): CidrBlockState
    {
        $this->statusMessage = $statusMessage;
        return $this;
    }
}
