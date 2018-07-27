<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 10/08/17
 * Time: 15:00
 */

namespace DatabaseBundle\DataFixtures\NoSql\Configuration;

use EssentialsBundle\Collection\TypedCollectionAbstract;

/**
 * Class SeriesCollection
 * @package DatabaseBundle\DataFixtures\NoSql\Configuration
 */
class SeriesCollection extends TypedCollectionAbstract
{
    public function getClass(): string
    {
        return Series::class;
    }

    protected function onChanged($added = null, $removed = null)
    {
    }
}
