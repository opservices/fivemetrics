<?php

namespace EssentialsBundle\Api;

trait ResultTrait
{
    /**
     * @param string $period
     * @param string $metricName
     * @param int $value
     * @param array $points
     * @param array $extra
     * @return array
     */
    public function createResult(string $period, string $metricName, float $value, array $points, array $extra = [])
    {
        $result = [
                'period' => $period,
                'series' => [
                    [
                        'name' => $metricName,
                        'value' => $value,
                        'points' => $points,
                    ]
                ],
            ];

        $result['series'][0] = array_merge($result['series'][0], $extra);
        return $result;
    }
}