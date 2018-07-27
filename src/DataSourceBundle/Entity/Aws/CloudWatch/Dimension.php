<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 10/02/17
 * Time: 13:20
 */

namespace DataSourceBundle\Entity\Aws\CloudWatch;

use DataSourceBundle\Entity\Aws\EntityAbstract;

/**
 * Class Dimension
 * @package DataSourceBundle\Entity\Aws\CloudWatch
 */
class Dimension extends EntityAbstract
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
     * Dimension constructor.
     * @param string $name
     * @param string $value
     */
    public function __construct(string $name, string $value)
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
     * @return Dimension
     */
    public function setName(string $name): Dimension
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return Dimension
     */
    public function setValue(string $value): Dimension
    {
        $this->value = $value;
        return $this;
    }
}
