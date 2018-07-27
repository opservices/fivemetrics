<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 16/08/17
 * Time: 09:35
 */

namespace DatabaseBundle\Command\NoSqlImporter;

class NoSqlMeasurement
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $time;

    /**
     * NoSqlMeasurement constructor.
     * @param string $name
     * @param int $time
     */
    public function __construct(string $name, int $time = 0)
    {
        $this->name = $name;
        $this->setTime($time);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getTime(): int
    {
        return $this->time;
    }

    /**
     * @param int $time
     * @return NoSqlMeasurement
     */
    public function setTime(int $time): NoSqlMeasurement
    {
        if ($time < 0) {
            throw new \InvalidArgumentException(
                "A time value can't be lower than 0."
            );
        }
        $this->time = $time;
        return $this;
    }
}
