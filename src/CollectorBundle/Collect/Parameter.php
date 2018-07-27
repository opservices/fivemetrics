<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 25/09/17
 * Time: 15:04
 */

namespace CollectorBundle\Collect;

use EssentialsBundle\Entity\EntityAbstract;

class Parameter extends EntityAbstract
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $value;

    /**
     * Parameter constructor.
     * @param string $name
     * @param mixed $value
     */
    public function __construct(string $name, $value)
    {
        $this->setName($name)
            ->setValue($value);
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
     * @return Parameter
     */
    public function setName(string $name): Parameter
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return Parameter
     */
    public function setValue($value): Parameter
    {
        $this->value = $value;
        return $this;
    }
}
