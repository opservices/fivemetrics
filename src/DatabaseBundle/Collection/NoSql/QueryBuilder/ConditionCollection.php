<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 31/05/17
 * Time: 12:55
 */

namespace DatabaseBundle\Collection\NoSql\QueryBuilder;

use DatabaseBundle\NoSql\QueryBuilder\Operators\LogicalOperatorInterface;
use EssentialsBundle\Collection\TypedCollectionAbstract;

/**
 * Class ConditionCollection
 * @package DatabaseBundle\Collection\NoSql\QueryBuilder
 */
class ConditionCollection extends TypedCollectionAbstract
{
    /**
     * @var LogicalOperatorInterface
     */
    protected $operator;

    /**
     * ConditionCollection constructor.
     * @param LogicalOperatorInterface $operator
     * @param array $elements
     */
    public function __construct(
        LogicalOperatorInterface $operator,
        array $elements = []
    ) {
        parent::__construct($elements);
        $this->operator = $operator;
    }

    public function getClass(): string
    {
        return "DatabaseBundle\NoSql\QueryBuilder\Condition";
    }

    protected function onChanged($added = null, $removed = null)
    {
    }

    public function __toString(): string
    {
        return $this->operator->setElements($this);
    }
}
