<?php

namespace DataSourceBundle\Aws\CostExplorer\Parser;

class ServiceCost implements ParserInterface
{

    /**
     * @inheritdoc
     */
    public function parse(array $costExplorerData): ResultSet
    {
        return array_reduce(
            $costExplorerData['ResultsByTime'][0]['Groups'],
            [$this, 'reduce'],
            new ResultSet()
        );
    }

    /**
     * @inheritdoc
     */
    public function reduce(ResultSet $carr, array $item)
    {
        $price = $item['Metrics']['UnblendedCost'];
        $carr->increaseAmount($price['Amount']);
        $carr->addPoint([
            'service' => $item['Keys'][0],
            'amount' => $price['Amount'],
            'currency' => $price['Unit']
        ]);

        return $carr;
    }
}