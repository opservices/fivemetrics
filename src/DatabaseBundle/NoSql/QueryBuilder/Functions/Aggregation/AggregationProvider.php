<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 02/06/17
 * Time: 10:20
 */

namespace DatabaseBundle\NoSql\QueryBuilder\Functions\Aggregation;

use function array_key_exists;
use DatabaseBundle\NoSql\QueryBuilder\Column;
use EssentialsBundle\Exception\Exceptions;

/**
 * Class AggregationProvider
 * @package DatabaseBundle\NoSql\QueryBuilder\Functions\Aggregation
 */
class AggregationProvider
{
    protected const METHODS = [
        'sum'   => 'Sum',
        'count' => 'Count',
        'max'   => 'Max',
        'min'   => 'Min',
        'mean'  => 'Mean',
    ];

    public static function factory(
        string $method,
        string $column = "value"
    ): AggregationInterface {
        $method = strtolower($method);
        if (array_key_exists($method, self::METHODS)) {
            $class = __NAMESPACE__ . '\\' . self::METHODS[$method];
            return new $class(new Column($column));
        }

        throw new \InvalidArgumentException(
            "An invalid aggregation method has been provided.",
            Exceptions::VALIDATION_ERROR
        );
    }
}
