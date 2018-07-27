<?php

namespace DataSourceBundle\Aws\CostExplorer\Parser;

use EssentialsBundle\Entity\DateTime\DateTime;

class CostDaily extends Cost
{
    /**
     * @inheritdoc
     */
    protected function reduce(ResultSet $carr, array $item): ResultSet
    {
        $price = $item['Total']['UnblendedCost'];
        $carr->increaseAmount($price['Amount']);
        $carr->setCurrency($price['Unit']);
        $carr->addPoint([
            'time' => DateTime::createFromFormat('Y-m-d', $item['TimePeriod']['End']),
            'amount' => $price['Amount'],
            'currency' => $price['Unit']
        ]);
        return $carr;
    }
}