<?php

namespace DataSourceBundle\Aws\CostExplorer\Parser;

class CostMonthly extends Cost
{
    /**
     * @inheritdoc
     */
    protected function reduce(ResultSet $carr, array $item): ResultSet
    {
        $price = $item['Total']['UnblendedCost'];
        $carr->increaseAmount($price['Amount']);
        $carr->setCurrency($price['Unit']);
        return $carr;
    }
}