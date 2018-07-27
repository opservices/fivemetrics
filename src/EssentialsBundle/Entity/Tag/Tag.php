<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 20/01/17
 * Time: 16:13
 */

namespace EssentialsBundle\Entity\Tag;

use EssentialsBundle\Entity\EntityAbstract;
use EssentialsBundle\Exception\Exceptions;

/**
 * Class Tag
 * @package DataSourceBundle\Entity\Tag
 */
class Tag extends EntityAbstract implements TagInterface
{
    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $value;

    /**
     * Tag constructor.
     * @param string $key
     * @param string $value
     */
    public function __construct(string $key, string $value = null)
    {
        $this->setKey($key)
            ->setValue((is_null($value)) ? '' : $value);
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return Tag
     */
    public function setKey(string $key)
    {
        if (empty($key)) {
            throw new \InvalidArgumentException(
                "An invalid key has been provided.",
                Exceptions::VALIDATION_ERROR
            );
        }

        $this->key = $key;
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
     * @return Tag
     */
    public function setValue(string $value)
    {
        $this->value = $value;
        return $this;
    }

    public function __toString()
    {
        return $this->getKey() . ':' . $this->getValue();
    }
}
