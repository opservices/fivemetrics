<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 14/09/17
 * Time: 09:01
 */

namespace DataSourceBundle\Api\V1\DataSourceCollect;

use DataSourceBundle\Api\V1\DataSourceRequestParameter;
use DataSourceBundle\Collection\Api\V1\DataSourceRequestParameterCollection;

class RequestDataSourceParameterBuilder
{
    public static function factory(array $dsParameters): DataSourceRequestParameterCollection
    {
        $collection = new DataSourceRequestParameterCollection();

        foreach ($dsParameters as $dsParameter) {
            $collection->add(new DataSourceRequestParameter(
                $dsParameter['name'],
                $dsParameter['value']
            ));
        }

        return $collection;
    }
}
