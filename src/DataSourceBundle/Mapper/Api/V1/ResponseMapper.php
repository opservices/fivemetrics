<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 22/09/17
 * Time: 14:32
 */

namespace DataSourceBundle\Mapper\Api\V1;

use DataSourceBundle\Entity\DataSource\DataSource;
use DataSourceBundle\Entity\DataSource\DataSourceCollect;
use DataSourceBundle\Entity\DataSource\DataSourceParameterValue;
use EssentialsBundle\Entity\Account\Account;
use EssentialsBundle\Entity\DateTime\DateTime;

class ResponseMapper
{
    public function getCollectsResponse(
        array $collects,
        array $fields = []
    ): array {
        $map = [];
        $time = new DateTime();

        foreach ($collects as $collect) {
            /** @var DataSourceCollect $collect */
            $email = $collect->getAccount()->getEmail();

            (isset($map[$email])) ?: $map[$email] = $this->getNewAccountBlock(
                $fields,
                $collect->getAccount(),
                $time
            );

            if (! isset($fields['collects'])) {
                continue;
            }

            if (! isset($map[$email]['collects'])) {
                $map[$email]['collects'] = [];
            }

            $i = count($map[$email]['collects']);

            if (isset($fields['collects']['dataSource'])) {
                $map[$email]['collects'][$i]['dataSource'] = $this->map(
                    $fields['collects']['dataSource'],
                    $this->getDataSourceArray($collect->getDataSource())
                );
            }

            if (isset($fields['collects']['parameters'])) {
                $map[$email]['collects'][$i]['parameters'] = $this->map(
                    $fields['collects']['parameters'],
                    $this->getParametersArray($collect)
                );
            }

            if (in_array('lastUpdate', $fields['collects'])) {
                $lastUpdate = $collect->getLastUpdate();

                if ($lastUpdate) {
                    /** @var \DateTime $lastUpdate */
                    $lastUpdate = $lastUpdate->format(DateTime::RFC3339);
                }

                $map[$email]['collects'][$i]['lastUpdate'] = $lastUpdate;
            }

            if (in_array('uid', $fields['collects'])) {
                $map[$email]['collects'][$i]['uid'] = $collect->getUid();
            }

            if (in_array('isEnabled', $fields['collects'])) {
                $map[$email]['collects'][$i]['isEnabled'] = $collect->isEnabled();
            }

            if (in_array('id', $fields['collects'])) {
                $map[$email]['collects'][$i]['id'] = $collect->getId();
            }
        }

        return array_values($map);
    }

    protected function map(array $fields, array $attributes): array
    {
        $result = [];

        foreach ($attributes as $key => $value) {
            if ((is_array($value)) && (array_values($value) != $value)) {
                $result[] = $this->map($fields, $value);
            }

            if (in_array($key, $fields)) {
                $result[$key] = $value;
            }

        }

        return $result;
    }

    protected function getNewAccountBlock(array $fields, Account $account, DateTime $time): array
    {
        $block = [];

        if (in_array('time', $fields)) {
            $block['time'] = (string)$time;
        }

        if (is_array($fields['account'])) {
            $block['account'] = $this->map(
                $fields['account'],
                $account->toArray()
            );
        }

        return $block;
    }

    protected function getParametersArray(DataSourceCollect $collect): array
    {
        return $collect->getParameterValues()
            ->map(function (DataSourceParameterValue $el) {
                return [
                    'name' => $el->getParameter()->getName(),
                    'value' => $el->getValue(),
                ];
            })->toArray();
    }

    protected function getDataSourceArray(
        DataSource $ds
    ): array {
        $conf = $ds->getDataSourceConfiguration();

        return [
            'name' => $ds->getName(),
            'maxConcurrency' => $conf->getMaxConcurrency(),
            'collectInterval' => $conf->getCollectInterval(),
        ];
    }
}
