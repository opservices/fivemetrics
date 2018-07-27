<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 21/09/17
 * Time: 10:46
 */

namespace DataSourceBundle\Entity\DataSource;

use EssentialsBundle\Entity\Builder\EntityBuilderAbstract;

class DataSourceParameterValueBuilder extends EntityBuilderAbstract
{
    public function factory(array $data, array $validationGroups = [])
    {
        $dsParamValues = $this->getInstance(DataSourceParameterValue::class, $data);

        if (! empty($validationGroups)) {
            $this->validate($dsParamValues, $validationGroups);
        }

        return $dsParamValues;
    }
}
