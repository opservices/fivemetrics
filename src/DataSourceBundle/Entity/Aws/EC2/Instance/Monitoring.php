<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 24/01/17
 * Time: 14:20
 */

namespace DataSourceBundle\Entity\Aws\EC2\Instance;

use DataSourceBundle\Entity\Aws\EntityAbstract;

/**
 * Class Monitoring
 * @package DataSourceBundle\Entity\Aws\EC2\Instance
 */
class Monitoring extends EntityAbstract
{
    const STATE_TYPES = [
        'disabled',
        'disabling',
        'enabled',
        'pending'
    ];

    /**
     * @var string
     */
    protected $state;

    public function __construct(string $state)
    {
        $this->setState($state);
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
     * @return Monitoring
     */
    public function setState(string $state): Monitoring
    {
        if (! in_array($state, self::STATE_TYPES)) {
            throw new \InvalidArgumentException(
                'An invalid monitoring state type was provided: "' . $state . '""'
            );
        }

        $this->state = $state;
        return $this;
    }
}
