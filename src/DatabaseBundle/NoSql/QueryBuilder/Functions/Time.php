<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 31/05/17
 * Time: 18:27
 */

namespace DatabaseBundle\NoSql\QueryBuilder\Functions;

use DatabaseBundle\NoSql\QueryBuilder\QueryElementAbstract;
use EssentialsBundle\Exception\Exceptions;

class Time extends QueryElementAbstract
{
    const INTERVALS = [
        'second' => '1s',
        'minute' => '1m',
        'hour' => '1h',
        'day' => '1d',
    ];

    /**
     * @var string;
     */
    protected $interval;

    /**
     * Time constructor.
     * @param string $interval
     */
    public function __construct(string $interval)
    {
        $this->setInterval($interval);
    }

    /**
     * @param string $interval
     * @return Time
     */
    protected function setInterval(string $interval): Time
    {
        $interval = strtolower($interval);

        if (! in_array($interval, array_keys(self::INTERVALS))) {
            throw new \InvalidArgumentException(
                'An invalid interval has been provided.',
                Exceptions::VALIDATION_ERROR
            );
        }

        $this->interval = $interval;
        return $this;
    }

    public function __toString(): string
    {
        return $this->getElementName()
            . '(' . self::INTERVALS[$this->interval] . ')';
    }
}
