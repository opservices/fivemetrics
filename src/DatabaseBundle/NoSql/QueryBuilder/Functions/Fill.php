<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 8/4/17
 * Time: 11:11 AM
 */

namespace DatabaseBundle\NoSql\QueryBuilder\Functions;

use DatabaseBundle\NoSql\QueryBuilder\QueryElementAbstract;

/**
 * Class Fill
 * @package DatabaseBundle\NoSql\QueryBuilder\Functions
 */
class Fill extends QueryElementAbstract
{
    /**
     * @var int
     */
    protected $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getElementName()
            . '(' . $this->value . ')';
    }
}
