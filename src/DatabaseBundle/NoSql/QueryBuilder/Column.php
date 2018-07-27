<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 31/05/17
 * Time: 12:52
 */

namespace DatabaseBundle\NoSql\QueryBuilder;

use EssentialsBundle\Exception\Exceptions;

/**
 * Class Column
 * @package DatabaseBundle\NoSql\QueryBuilder
 */
class Column extends QueryElementAbstract
{
    /**
     * @var string
     */
    protected $name;

    /**
     * Column constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->setName($name);
    }

    /**
     * @param string $name
     * @return Column
     */
    protected function setName(string $name): Column
    {
        if (empty($name)) {
            throw new \InvalidArgumentException(
                "An empty column name has been provided.",
                Exceptions::VALIDATION_ERROR
            );
        }

        $this->name = $name;

        return $this;
    }

    public function __toString(): string
    {
        return '"' . $this->name . '"';
    }
}
