<?php

namespace DataSourceBundle\Aws\CostExplorer;

use DataSourceBundle\Aws\CostExplorer\Parser\CostDaily;
use DataSourceBundle\Aws\CostExplorer\Parser\CostMonthly;
use DataSourceBundle\Aws\CostExplorer\Parser\ParserInterface;
use DataSourceBundle\Aws\CostExplorer\Parser\RawCost;
use DataSourceBundle\Aws\CostExplorer\Parser\ServiceCost;
use DataSourceBundle\Aws\CostExplorer\Parser\TypeEnum;
use EssentialsBundle\Entity\TimePeriod\TimePeriodAbstract;

class ParserFactory
{
    /**
     * @param TimePeriodAbstract|null $timePeriod
     * @param string $granularity
     * @return ParserInterface
     */
    public function create(
        TimePeriodAbstract $timePeriod = null,
        string $granularity = GranularityEnum::MONTHLY
    ): ParserInterface {

        if (is_null($timePeriod)) {
            return new ServiceCost();
        }

        return GranularityEnum::MONTHLY == $granularity
            ? new CostMonthly($timePeriod)
            : new CostDaily($timePeriod);
    }
}