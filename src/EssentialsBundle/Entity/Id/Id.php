<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 19/05/17
 * Time: 08:29
 */

namespace EssentialsBundle\Entity\Id;

use EssentialsBundle\Entity\EntityAbstract;
use EssentialsBundle\Exception\Exceptions;

/**
 * Class Id
 * @package EssentialsBundle\Entity\NoSql
 */
class Id extends EntityAbstract implements IdInterface
{
    /**
     * @var string
     */
    protected $value;

    /**
     * Id constructor.
     * @param string $value
     */
    public function __construct(string $value)
    {
        $this->setValue($value);
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
     * @return Id
     */
    protected function setValue(string $value): Id
    {
        if (! preg_match('/^([0-9a-zA-Z_]|-)+$/', $value)) {
            throw new \InvalidArgumentException(
                'An invalid id value has been provided.',
                Exceptions::VALIDATION_ERROR
            );
        }

        $this->value = $value;
        return $this;
    }

    public function __toString()
    {
        return $this->getValue();
    }
}
