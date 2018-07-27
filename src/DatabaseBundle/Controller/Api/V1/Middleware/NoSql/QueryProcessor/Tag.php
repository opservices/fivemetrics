<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 04/01/18
 * Time: 09:18
 */

namespace DatabaseBundle\Controller\Api\V1\Middleware\NoSql\QueryProcessor;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class Tag
 * @package DatabaseBundle\Controller\Api\V1\Middleware\NoSql\QueryProcessor
 */
class Tag
{
    protected const DEFAULT_QUERY = '{"metrics":[],"type":"all"}';

    /**
     * @var array
     */
    protected $query = [];

    /**
     * @param Request $request
     * @return Tag
     */
    protected function setRequestQuery(Request $request): Tag
    {
        $this->query = json_decode(
            $request->query->get('q', self::DEFAULT_QUERY),
            true
        );

        return $this;
    }

    protected function updateQueryWithDefaultValues(): Tag
    {
        $type = $this->query['type'] ?? 'all';
        $this->query['type'] = [];

        $this->query['type']['custom'] = (($type == 'all') || ($type == 'custom'));
        $this->query['type']['system'] = (($type == 'all') || ($type == 'system'));

        if (! is_array($this->query['metrics'])) {
            $this->query['metrics'] = [];
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getQueryParameters(Request $request): array
    {
        $this->setRequestQuery($request)
            ->updateQueryWithDefaultValues();

        return $this->query;
    }
}
