<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 20/01/17
 * Time: 15:08
 */

namespace DataSourceBundle\Entity\Aws\EC2\Instance;

use DataSourceBundle\Entity\Aws\EntityAbstract;

/**
 * Class StateReason
 * @package DataSourceBundle\Entity\Aws\EC2\Instance
 */
class StateReason extends EntityAbstract
{
    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $message;

    /**
     * StateReason constructor.
     * @param string $code
     * @param string $message
     */
    public function __construct(string $code, string $message)
    {
        $this->setCode($code)
            ->setMessage($message);
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return StateReason
     */
    public function setCode(string $code): StateReason
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     * @return StateReason
     */
    public function setMessage(string $message): StateReason
    {
        $this->message = $message;
        return $this;
    }
}
