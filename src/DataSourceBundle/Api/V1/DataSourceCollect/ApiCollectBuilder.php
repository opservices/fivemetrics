<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 15/09/17
 * Time: 14:28
 */

namespace DataSourceBundle\Api\V1\DataSourceCollect;

use DataSourceBundle\Entity\DataSource\DataSource;
use DataSourceBundle\Entity\DataSource\DataSourceCollect;
use DataSourceBundle\Entity\DataSource\DataSourceParameter;
use DataSourceBundle\Entity\DataSource\DataSourceParameterValue;
use EssentialsBundle\Entity\Account\Account;

class ApiCollectBuilder
{
    public function factory(
        Account $account,
        DataSource $dataSource,
        array $parameters
    ): DataSourceCollect {
        $collect = new DataSourceCollect();
        $collect->setAccount($account)
            ->setDataSource($dataSource);

        foreach ($parameters as $name => $value) {
            $collect->getParameterValues()->add(new DataSourceParameterValue(
                $dataSource,
                $account,
                new DataSourceParameter($name, $dataSource),
                null,
                $value
            ));
        }

        return $collect;
    }
}
