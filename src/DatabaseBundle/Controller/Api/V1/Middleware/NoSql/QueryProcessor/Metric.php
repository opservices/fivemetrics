<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 25/05/17
 * Time: 09:55
 */

namespace DatabaseBundle\Controller\Api\V1\Middleware\NoSql\QueryProcessor;

use EssentialsBundle\Exception\Exceptions;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class MetricsMetricsQueryProcessor
 * @package DatabaseBundle\Controller\Api\V1\NoSql
 */
class Metric
{
    protected const DEFAULT_QUERY = '{"periods":["last5minutes"],"query":{}}';

    protected const MAX_NESTED_QUERY = 5;

    /**
     * @var array
     */
    protected $query = [];

    /**
     * @param Request $request
     * @return Metric
     */
    protected function setRequestQuery(Request $request): Metric
    {
        $this->query = json_decode(
            $request->query->get('q', self::DEFAULT_QUERY),
            true
        );

        return $this;
    }

    protected function updateQueryWithDefaultValues(): Metric
    {
        (isset($this->query['periods'])) ?: $this->query['periods'] = [ 'last5minutes' ];
        (isset($this->query['query'])) ?: $this->query['query'] = [];
        return $this;
    }

    /**
     * @return array
     */
    public function getQueryParameters(Request $request): array
    {
        $this->setRequestQuery($request)
            ->updateQueryWithDefaultValues()
            ->validateAttributes()
            ->validateNestedQueryLevel($this->query);

        return $this->query;
    }

    protected function validateAttributes()
    {
        $isValidPeriods = ((is_array($this->query['periods']))
            && (! empty($this->query['periods'])));

        $isValidQuery = (is_array($this->query['query']));

        if (($isValidPeriods) && ($isValidQuery)) {
            return $this;
        }

        throw new \InvalidArgumentException(
            "An invalid request query has been provided.",
            Exceptions::VALIDATION_ERROR
        );
    }

    /**
     * @param array $query
     * @return Metric
     */
    protected function validateNestedQueryLevel(
        array $query,
        int $nestedLevel = 0
    ): Metric {
        if ($nestedLevel > self::MAX_NESTED_QUERY) {
            throw new \InvalidArgumentException(
                "The maximum nested query level is " . self::MAX_NESTED_QUERY . ".",
                Exceptions::VALIDATION_ERROR
            );
        }

        return (empty($query['query']))
            ? $this
            : $this->validateNestedQueryLevel($query['query'], $nestedLevel + 1);
    }
}
