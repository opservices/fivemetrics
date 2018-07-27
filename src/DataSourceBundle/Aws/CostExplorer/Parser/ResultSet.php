<?php

namespace DataSourceBundle\Aws\CostExplorer\Parser;

class ResultSet implements \JsonSerializable
{
    /**
     * @var array
     */
    protected $result = [
        'forecast' => 0,
        'amount' => 0,
        'currency' => 'USD',
        'points' => []
    ];

    /**
     * @param int $forecastDays
     * @param int $totalDays
     */
    public function calculateForecast(int $forecastDays, int $totalDays)
    {
        $averageDayCost = $this->result['amount'] / $totalDays;
        $this->result['forecast'] = $this->result['amount'] + ($averageDayCost * $forecastDays);
    }

    /**
     * @param float $value
     */
    public function increaseAmount(float $value)
    {
        $this->result['amount'] += ($value >= 0) ? $value : 0;
    }

    /**
     * @param array $point
     * @return $this
     */
    public function addPoint(array $point)
    {
        if (! empty($point)) {
            $this->result['points'][] = $point;
        }

        return $this;
    }

    /**
     * @param array $points
     * @return $this
     */
    public function addPoints(array $points)
    {
        $this->result['points'] = array_merge($this->result['points'], $points);
        return $this;
    }

    /**
     * @param string $currency
     * @return $this
     */
    public function setCurrency(string $currency)
    {
        $this->result['currency'] = empty($currency) ? 'USD' : $currency;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->result;
    }

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return $this->result;
    }
}