<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 20/01/17
 * Time: 10:48
 */

namespace DataSourceBundle\Entity\Aws\EC2\Instance;

use DataSourceBundle\Entity\Aws\EntityAbstract;

/**
 * Class InstanceState
 * @package DataSourceBundle\Entity\Aws\EC2\Instance
 */
class InstanceState extends EntityAbstract
{
    const INSTANCE_STATE_NAMES = [
        'pending',
        'running',
        'shutting-down',
        'terminated',
        'stopping',
        'stopped'
    ];

    /**
     * @var int
     */
    protected $code;

    /**
     * @var string
     */
    protected $name;

    /**
     * InstanceState constructor.
     * @param int $code
     * @param string $name
     */
    public function __construct(int $code, string $name)
    {
        $this->setName($name)
            ->setCode($code);
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param int $code
     * @return InstanceState
     */
    public function setCode(int $code): InstanceState
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @param string $name
     * @return InstanceState
     */
    public function setName(string $name): InstanceState
    {
        if (! in_array($name, self::INSTANCE_STATE_NAMES)) {
            throw new \InvalidArgumentException(
                'An invalid state name was provided:'
                . ' "' . $name . '"'
            );
        }

        $this->name = $name;
        return $this;
    }


}
