<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 01/06/17
 * Time: 08:10
 */

namespace DatabaseBundle\NoSql\QueryBuilder;

use EssentialsBundle\Exception\Exceptions;

/**
 * Class Condition
 * @package DatabaseBundle\NoSql\QueryBuilder
 */
class Condition extends QueryElementAbstract
{
    const OPERATORS = [
        '=',
        '<>',
        '!=',
        '>',
        '>=',
        '<',
        '<=',
    ];

    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $operator;

    /**
     * @var string
     */
    protected $value;

    /**
     * Condition constructor.
     * @param string $key
     * @param string $operator
     * @param string $value
     */
    public function __construct(string $key, string $operator, string $value = null)
    {
        $this->setKey($key)
            ->setOperator($operator);

        (is_null($value)) ?: $this->setValue($value);
    }

    /**
     * @param string $operator
     * @return Condition
     */
    protected function setOperator(string $operator): Condition
    {
        if (! in_array($operator, self::OPERATORS)) {
            throw new \InvalidArgumentException(
                'The "' . $operator . '" is not supported.',
                Exceptions::VALIDATION_ERROR
            );
        }

        $this->operator = $operator;
        return $this;
    }

    /**
     * @param string $key
     * @return Condition
     */
    protected function setKey(string $key): Condition
    {
        if (empty($key)) {
            throw new \InvalidArgumentException(
                'An invalid condition has been provided.',
                Exceptions::VALIDATION_ERROR
            );
        }

        $this->key = $key;
        return $this;
    }

    /**
     * @param string $value
     * @return Condition
     */
    protected function setValue(string $value): Condition
    {
        $this->value = $value;
        return $this;
    }

    public function __toString(): string
    {
        $value = "'" . $this->value . "'";
        if ($this->key == 'time') {
            $value = $this->value;
        } elseif (is_null($this->value)) {
            $value = "''";
        } elseif ((! is_numeric($this->value)) && (empty($this->value))) {
            $value = '\'""\'';
        }

        return sprintf(
            '("%s" %s %s)',
            $this->key,
            $this->operator,
            $value
        );
    }
}
