<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 7/25/17
 * Time: 2:14 PM
 */

namespace DataSourceBundle\Aws\Glacier\Measurement\Glacier\Vault;

use DataSourceBundle\Aws\MeasurementInterface;
use DataSourceBundle\Collection\Aws\Glacier\Vault\VaultCollection;
use DataSourceBundle\Entity\Aws\Region\RegionInterface;
use EssentialsBundle\Entity\DateTime\DateTime;

/**
 * Class MeasurementAbstract
 * @package DataSourceBundle\Aws\Glacier\Measurement\Glacier\Vault
 */
abstract class MeasurementAbstract extends \DataSourceBundle\Aws\MeasurementAbstract implements MeasurementInterface
{

    /**
     * @var VaultCollection
     */
    protected $vaults;

    /**
     * MeasurementAbstract constructor.
     * @param RegionInterface $region
     * @param DateTime $dateTime
     * @param VaultCollection $vaults
     */
    public function __construct(
        RegionInterface $region,
        Datetime $dateTime,
        VaultCollection $vaults
    ) {
        parent::__construct($region, $dateTime);
        $this->setVaults($vaults);
    }

    /**
     * @return VaultCollection
     */
    public function getVaults(): VaultCollection
    {
        return $this->vaults;
    }

    /**
     * @param VaultCollection $vaults
     * @return MeasurementAbstract
     */
    public function setVaults(VaultCollection $vaults): MeasurementAbstract
    {
        $this->vaults = $vaults;
        return $this;
    }

    /**
     * @return array
     */
    protected function getNameParts(): array
    {
        return array_merge(
            parent::getNameParts(),
            [ 'glacier' ]
        );
    }

    /**
     * @return array
     */
    protected function getTags(): array
    {
        $tags = parent::getTags();

        $tags[] = [
            'key' => '::fm::region',
            'value' => $this->getRegion()->getCode()
        ];

        return $tags;
    }
}
