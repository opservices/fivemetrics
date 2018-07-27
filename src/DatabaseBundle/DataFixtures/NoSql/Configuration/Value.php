<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 10/08/17
 * Time: 15:03
 */

namespace DatabaseBundle\DataFixtures\NoSql\Configuration;

use EssentialsBundle\Entity\EntityAbstract;

class Value extends EntityAbstract
{
    const TYPES = [
        'fixed',
        'random'
    ];

    /**
     * @var string
     */
    protected $type;

    /**
     * @var mixed
     */
    protected $data;

    /**
     * Value constructor.
     * @param string $type
     * @param $data
     */
    public function __construct(string $type, $data)
    {
        $this->setType($type)
            ->setData($data);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Value
     */
    public function setType(string $type): Value
    {
        if (! in_array($type, self::TYPES)) {
            throw new \InvalidArgumentException(
                'An invalid type has been provided.'
            );
        }

        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    protected function setData($data): Value
    {
        $this->validateData($data);
        $this->data = $data;

        return $this;
    }

    protected function validateData($data): bool
    {
        if ($this->getType() == 'fixed') {
            if ((is_numeric($data)) || (is_string($data))) {
                return true;
            }

            throw new \InvalidArgumentException(
                'A fixed value must be a number or a string.'
            );
        }

        if ($this->getType() == 'random') {
            if ((is_array($data)) || (is_a($data, Range::class))) {
                return true;
            }

            throw new \InvalidArgumentException(
                'A random value must be an array or a Range object.'
            );
        }

        throw new \RuntimeException(
            "Something happen... I don't know what."
        );
    }
}
