<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 10/08/17
 * Time: 15:02
 */

namespace DatabaseBundle\DataFixtures\NoSql\Configuration;

use EssentialsBundle\Entity\EntityAbstract;

/**
 * Class Tag
 * @package DatabaseBundle\DataFixtures\NoSql\Configuration
 */
class Tag extends EntityAbstract
{
    /**
     * @var string
     */
    protected $key;

    /**
     * @var Value
     */
    protected $value;

    /**
     * Tag constructor.
     * @param string $key
     * @param Value $value
     */
    public function __construct(string $key, Value $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return Value
     */
    public function getValue(): Value
    {
        return $this->value;
    }
}
