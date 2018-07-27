<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 7/27/17
 * Time: 10:50 AM
 */

namespace DataSourceBundle\Aws\Glacier\Measurement\Glacier\Vault;

use DataSourceBundle\Collection\Aws\Glacier\Vault\VaultCollection;
use DataSourceBundle\Entity\Aws\Region\RegionInterface;
use EssentialsBundle\Collection\Metric\MetricCollection;
use DataSourceBundle\Entity\Aws\Glacier\Vault\Vault;
use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\Entity\Metric\Builder;

/**
 * Class VaultArchive
 * @package DataSourceBundle\Aws\Glacier\Measurement\Glacier\Vault
 */
class VaultArchive extends MeasurementAbstract
{
    /**
     * VaultArchive constructor.
     * @param RegionInterface $region
     * @param DateTime $dateTime
     * @param VaultCollection $vaults
     */
    public function __construct(
        RegionInterface $region,
        DateTime $dateTime,
        VaultCollection $vaults
    ) {
        parent::__construct($region, $dateTime, $vaults);
    }

    /**
     * @return MetricCollection
     */
    public function getMetrics(): MetricCollection
    {
        $buildData = [];
        $vaults = $this->getVaults();

        /**
         * @var $vault Vault
         */
        foreach ($vaults as $vault) {
            $key = $this->getRegion()->getCode() . $vault->getVaultName();

            if (! isset($buildData[$key])) {
                $buildData[$key] = [
                    'name' => $this->getName(['vault', 'archive']),
                    'tags' => $this->getTags($vault),
                    'points' => [
                        [
                            'value' => $vault->getNumberOfArchives(),
                            'time' => $this->getMetricsDatetime()
                        ]
                    ]
                ];
            }
        }
        return Builder::build(array_values($buildData));
    }

    /**
     * @param Vault $vault
     * @return array
     */
    protected function getTags(Vault $vault): array
    {
        $tags = parent::getTags();

        $tags[] = [
            'key' => '::fm::vaultName',
            'value' => $vault->getVaultName()
        ];

        $awsTags = array_map(function ($tag) {
            return [
                'key'   => $tag['Key'],
                'value' => $tag['Value'],
            ];
        }, $vault->getTags()->toArray());

        return array_merge($tags, $awsTags);
    }
}
