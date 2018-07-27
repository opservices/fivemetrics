<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 13/06/17
 * Time: 10:54
 */

namespace DatabaseBundle\NoSql\QueryBuilder\Statement;

use DatabaseBundle\NoSql\QueryBuilder\QueryElementAbstract;
use EssentialsBundle\Exception\Exceptions;

/**
 * Class OrderBy
 * @package DatabaseBundle\NoSql\QueryBuilder\Statement
 */
class OrderBy extends QueryElementAbstract
{
    const VALID_ORDERS = [
        'OLDEST' => 'ASC',
        'NEWEST' => 'DESC'
    ];

    /**
     * @var string
     */
    protected $order;

    /**
     * OrderBy constructor.
     * @param string $order
     */
    public function __construct(string $order = 'newest')
    {
        $this->setOrder($order);
    }

    /**
     * @param string $order
     * @return OrderBy
     */
    protected function setOrder(string $order): OrderBy
    {
        $order = strtoupper($order);

        if (! array_key_exists($order, self::VALID_ORDERS)) {
            throw new \InvalidArgumentException(
                'An invalid order has been provided.',
                Exceptions::VALIDATION_ERROR
            );
        }

        $this->order = $order;

        return $this;
    }

    public function __toString(): string
    {
        return 'ORDER BY "time" ' . self::VALID_ORDERS[$this->order];
    }
}
