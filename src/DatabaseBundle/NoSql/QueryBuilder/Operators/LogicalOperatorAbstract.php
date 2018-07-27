<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 01/06/17
 * Time: 16:08
 */

namespace DatabaseBundle\NoSql\QueryBuilder\Operators;

use EssentialsBundle\Collection\TypedCollectionAbstract;

/**
 * Class LogicalOperatorAbstract
 * @package DatabaseBundle\NoSql\QueryBuilder\Operators
 */
abstract class LogicalOperatorAbstract implements LogicalOperatorInterface
{
    /**
     * @var TypedCollectionAbstract
     */
    protected $elements;

    /**
     * AndOperator constructor.
     * @param TypedCollectionAbstract $elements
     */
    public function __construct(
        TypedCollectionAbstract $elements = null
    ) {
        (is_null($elements)) ?: $this->setElements($elements);
    }

    public function setElements(
        TypedCollectionAbstract $elements
    ): LogicalOperatorInterface {
        $this->elements = $elements;
        return $this;
    }
}
