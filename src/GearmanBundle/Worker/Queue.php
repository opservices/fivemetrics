<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 24/02/17
 * Time: 15:53
 */

namespace GearmanBundle\Worker;

/**
 * Class Queue
 * @package GearmanBundle\Worker
 */
class Queue
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $method;

    /**
     * Queue constructor.
     * @param string $name
     * @param string $method
     */
    public function __construct(
        string $name,
        string $method
    ) {
        $this->setName($name)
            ->setMethod($method);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Queue
     */
    public function setName(string $name): Queue
    {
        if (empty($name)) {
            throw new \InvalidArgumentException(
                "The queue name can't be empty."
            );
        }

        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @param string $method
     * @return Queue
     */
    public function setMethod(string $method): Queue
    {
        $this->method = $method;
        return $this;
    }
}
