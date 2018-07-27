<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 21/09/17
 * Time: 10:54
 */

namespace DataSourceBundle\Entity\DataSource;

use EssentialsBundle\Entity\Builder\EntityBuilderAbstract;

class DataSourceParameterBuilder extends EntityBuilderAbstract
{
    public function factory(array $data, array $validationGroups = [])
    {
        $dsParam = $this->getInstance(DataSourceParameter::class, $data);

        if (! empty($validationGroups)) {
            $this->validate($dsParam, $validationGroups);
        }

        return $dsParam;
    }
}
