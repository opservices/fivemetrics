<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 14/09/17
 * Time: 08:41
 */

namespace DataSourceBundle\Api\V1;

use EssentialsBundle\Exception\Exceptions;

class DataSourceRequestParameter
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * DataSourceRequestParameter constructor.
     * @param string $name
     * @param string|int $value
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
     * @param mixed $name
     * @return DataSourceRequestParameter
     */
    public function setName(string $name): DataSourceRequestParameter
    {
        if (! preg_match('/^[a-zA-Z0-9][a-zA-Z0-9\-\_\.]+$/', $name)) {
            throw new \InvalidArgumentException(
                "The parameter name can't be empty and must have only letters, numbers, dots, \"-\" and \"_\"",
                Exceptions::VALIDATION_ERROR
            );
        }

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
     * @param string|int|float|boolean $value
     * @return DataSourceRequestParameter
     */
    public function setValue($value): DataSourceRequestParameter
    {
        $this->value = $value;
        return $this;
    }
}
