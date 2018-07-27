<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 28/01/17
 * Time: 23:06
 */

namespace EssentialsBundle\Entity\Shell\Command;

use EssentialsBundle\Entity\EntityAbstract;

/**
 * Class Argument
 * @package EssentialsBundle\Entity\Shell\Command
 */
class Argument extends EntityAbstract
{
    /**
     * @var
     */
    protected $name;

    /**
     * @var
     */
    protected $value;

    public function __construct(string $name, string $value = null)
    {
        $this->setName($name);
        if (! is_null($value)) {
            $this->setValue($value);
        }
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Argument
     */
    public function setName($name): Argument
    {
        $this->name = $this->escapeArgument($name);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return Argument
     */
    public function setValue($value): Argument
    {
        $this->value = $this->escapeArgument($value);
        return $this;
    }

    /**
     * @param $argument
     * @return string
     */
    public static function escapeArgument($argument): string
    {
        return escapeshellarg($argument);
    }

    /**
     * return string
     */
    public function __toString()
    {
        return sprintf(
            "%s%s",
            $this->getName(),
            (is_null($this->getValue())) ? "" : " " . $this->getValue()
        );
    }
}
