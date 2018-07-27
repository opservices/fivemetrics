<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 04/05/17
 * Time: 15:53
 */

namespace DataSourceBundle\Collection\Gearman\Queue\Collector\Generic;

use EssentialsBundle\Collection\TypedCollectionAbstract;

/**
 * Class ResultSetCollection
 * @package DataSourceBundle\Collection\Gearman\Queue\Collector\Generic
 */
class ResultSetCollection extends TypedCollectionAbstract
{
    public function getClass(): string
    {
        return 'DataSourceBundle\Gearman\Queue\Collector\Generic\ResultSet';
    }

    protected function onChanged($added = null, $removed = null)
    {
    }
}
