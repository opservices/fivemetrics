<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 21/09/17
 * Time: 10:11
 */

namespace DataSourceBundle\Entity\DataSource;

use EssentialsBundle\Entity\Builder\EntityBuilderAbstract;

class DataSourceBuilder extends EntityBuilderAbstract
{
    public function factory(array $data, array $validationGroups = [])
    {
        $ds = $this->getInstance(DataSource::class, $data);

        if (! empty($validationGroups)) {
            $this->validate($ds, $validationGroups);
        }

        return $ds;
    }
}
