<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 31/05/17
 * Time: 17:52
 */

namespace DatabaseBundle\NoSql\QueryBuilder\Statement;

use DatabaseBundle\NoSql\QueryBuilder\QueryElementAbstract;
use EssentialsBundle\Exception\Exceptions;

/**
 * Class Limit
 * @package DatabaseBundle\NoSql\QueryBuilder\Statement
 */
class Limit extends QueryElementAbstract
{
    const MAX = 1000;

    /**
     * @var int
     */
    protected $limit;

    /**
     * Limit constructor.
     * @param int $limit
     */
    public function __construct(int $limit = 250)
    {
        $this->setLimit($limit);
    }

    /**
     * @param int $limit
     * @return Limit
     */
    protected function setLimit(int $limit): Limit
    {
        if (($limit > self::MAX) || ($limit < 1)) {
            throw new \InvalidArgumentException(
                'The result limit must be between 1 and ' . self::MAX . '.',
                Exceptions::VALIDATION_ERROR
            );
        }

        $this->limit = $limit;
        return $this;
    }

    public function __toString(): string
    {
        return "LIMIT " . $this->limit;
    }
}
