<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 09/02/17
 * Time: 16:35
 */

namespace DataSourceBundle\Aws\EC2\Measurement\EC2;

use DataSourceBundle\Aws\MeasurementInterface;
use DataSourceBundle\Collection\Aws\EC2\Instance\InstanceCollection;
use DataSourceBundle\Entity\Aws\Region\RegionInterface;
use EssentialsBundle\Entity\DateTime\DateTime;

/**
 * Class MeasurementAbstract
 * @package DataSourceBundle\Aws\EC2\Measurement\EC2
 */
abstract class MeasurementAbstract
    extends \DataSourceBundle\Aws\MeasurementAbstract
    implements MeasurementInterface
{
    /**
     * @var InstanceCollection
     */
    protected $instances;

    /**
     * MeasurementAbstract constructor.
     * @param RegionInterface $region
     * @param InstanceCollection $instances
     */
    public function __construct(
        RegionInterface $region,
        DateTime $dateTime,
        InstanceCollection $instances
    ) {
        parent::__construct($region, $dateTime);
        $this->setInstances($instances);
    }

    /**
     * @return InstanceCollection
     */
    public function getInstances(): InstanceCollection
    {
        return $this->instances;
    }

    /**
     * @param InstanceCollection $instances
     * @return MeasurementAbstract
     */
    public function setInstances(InstanceCollection $instances): MeasurementAbstract
    {
        $this->instances = $instances;
        return $this;
    }

    /**
     * @return array
     */
    protected function getNameParts(): array
    {
        return array_merge(
            parent::getNameParts(),
            ['ec2']
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
