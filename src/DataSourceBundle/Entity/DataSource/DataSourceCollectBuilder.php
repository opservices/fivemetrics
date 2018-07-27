<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 12/09/17
 * Time: 17:08
 */

namespace DataSourceBundle\Entity\DataSource;

use EssentialsBundle\Entity\Builder\EntityBuilderAbstract;

class DataSourceCollectBuilder extends EntityBuilderAbstract
{
    public function factory(array $data, array $validationGroups = []): DataSourceCollect
    {
        $dsCollect = $this->getInstance(DataSourceCollect::class, $data);

        if (! empty($validationGroups)) {
            $this->validate($dsCollect, $validationGroups);
        }

        return $dsCollect;
    }
}
