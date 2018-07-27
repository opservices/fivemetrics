<?php

namespace DataSourceBundle\Aws\CostExplorer;

use DataSourceBundle\Aws\CostExplorer\GranularityEnum as GEnum;
use EssentialsBundle\Entity\TimePeriod\TimePeriodAbstract as TimePeriod;

class Config
{
    const DATE_FORMAT = 'Y-m-d';

    /**
     * @var array
     */
    protected $config = [
        'Granularity' => GEnum::MONTHLY,
        'Metrics' => ['UnblendedCost'],
    ];

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->config;
    }

    /**
     * @param string $granularity
     * @return $this
     */
    public function setGranularity(string $granularity)
    {
        $this->config['Granularity'] = (GEnum::DAILY == $granularity) ? GEnum::DAILY : GEnum::MONTHLY;
        return $this;
    }

    /**
     * @return $this
     */
    public function groupByService()
    {
        $this->config['GroupBy'] = [['Type' => 'DIMENSION', 'Key' => 'SERVICE']];
        return $this;
    }

    /**
     * @param array $config
     * @return $this
     */
    public function merge(array $config)
    {
        $this->config = array_merge($this->config, $config);
        return $this;
    }

    /**
     * @return $this
     */
    public function setTimePeriod(TimePeriod $timePeriod)
    {
        $this->config['TimePeriod']['Start'] = $timePeriod->getStart(self::DATE_FORMAT);
        $this->config['TimePeriod']['End'] = $timePeriod->getEnd(self::DATE_FORMAT);
        return $this;
    }

    public function getGranularity()
    {
        return $this->config['Granularity'];
    }

    public function isGroupedByService()
    {
        return [['Type' => 'DIMENSION', 'Key' => 'SERVICE']] == $this->config['GroupBy'];
    }
}
