<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 09/02/17
 * Time: 16:35
 */

namespace DataSourceBundle\Aws;

use DataSourceBundle\Entity\Aws\Region\RegionInterface;
use EssentialsBundle\Entity\DateTime\DateTime;
use EssentialsBundle\Entity\Metric\RealTimeData;

/**
 * Class MeasurementAbstract
 * @package DataSourceBundle\Aws
 */
abstract class MeasurementAbstract implements MeasurementInterface
{
    /**
     * @var RegionInterface
     */
    protected $region;

    /**
     * @var DateTime $datetime
     */
    protected $datetime;

    /**
     * @var RealTimeData
     */
    protected $realTimeData = null;

    /**
     * MeasurementAbstract constructor.
     * @param RegionInterface $region
     * @param DateTime|null $datetime
     */
    public function __construct(
        RegionInterface $region,
        DateTime $datetime = null
    ) {
        $this->setRegion($region)
            ->setMetricsDatetime((is_null($datetime)) ? new DateTime() : $datetime);
    }

    /**
     * @return RegionInterface
     */
    public function getRegion(): RegionInterface
    {
        return $this->region;
    }

    /**
     * @param RegionInterface $region
     * @return $this
     */
    public function setRegion(RegionInterface $region)
    {
        $this->region = $region;
        return $this;
    }

    /**
     * @return array
     */
    protected function getNameParts(): array
    {
        return [ 'aws' ];
    }

    /**
     * @return array
     */
    protected function getTags(): array
    {
        return [];
    }

    /**
     * @param array $additionalParts
     * @return string
     */
    protected function getName(array $additionalParts = []): string
    {
        return implode(
            '.',
            array_merge($this->getNameParts(), $additionalParts)
        );
    }

    /**
     * @return DateTime
     */
    public function getMetricsDatetime(): DateTime
    {
        return $this->datetime;
    }

    /**
     * @param DateTime $dateTime
     * @return MeasurementAbstract
     */
    protected function setMetricsDatetime(DateTime $dateTime): MeasurementAbstract
    {
        $this->datetime = $dateTime;
        return $this;
    }

    public function getRealTimeData()
    {
        return $this->realTimeData;
    }
}
