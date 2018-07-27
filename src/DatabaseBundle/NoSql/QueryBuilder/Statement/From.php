<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 31/05/17
 * Time: 14:02
 */

namespace DatabaseBundle\NoSql\QueryBuilder\Statement;

use DatabaseBundle\NoSql\QueryBuilder\QueryElementAbstract;
use EssentialsBundle\Exception\Exceptions;

/**
 * Class From
 * @package DatabaseBundle\NoSql\QueryBuilder\Statement
 */
class From extends QueryElementAbstract
{
    /**
     * @var mixed
     */
    protected $series;

    /**
     * @var bool
     */
    protected $isQueryInstance;

    /**
     * From constructor.
     * @param $series
     */
    public function __construct($series)
    {
        $this->setSeries($series);
    }

    /**
     * @param $series
     * @return $this
     */
    protected function setSeries($series)
    {
        if (! $this->isValidSeries($series)) {
            throw new \InvalidArgumentException(
                "An invalid series has been provided.",
                Exceptions::VALIDATION_ERROR
            );
        }

        $this->series = $series;

        return $this;
    }

    /**
     * @param $series
     * @return bool
     */
    protected function isValidSeries($series)
    {
        $this->isQueryInstance = (is_a(
            $series,
            'DatabaseBundle\NoSql\QueryBuilder\Query'
        ));

        return (($this->isQueryInstance)
            ||  ((is_string($series)) && (! empty($series))));
    }

    public function __toString(): string
    {
        $series = ($this->isQueryInstance)
            ? '(' . $this->series . ')'
            : '"' . $this->series . '"';

        return sprintf(
            '%s %s',
            $this->getElementName(),
            $series
        );
    }
}
