<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 25/10/17
 * Time: 08:27
 */

namespace CollectorBundle\Collect\Discovery;

use CollectorBundle\Collect\DataSource;
use CollectorBundle\Collect\ParameterCollection;
use DataSourceBundle\Entity\DataSource\DataSourceCollect;
use EssentialsBundle\Collection\Metric\MetricCollection;
use GearmanBundle\Collection\Job\JobCollection;

class Collect extends \CollectorBundle\Collect\Collect
{
    public function __construct(
        DataSource $dataSource,
        ParameterCollection $parameters,
        string $id = null,
        JobCollection $jobs = null,
        MetricCollection $metrics = null
    ) {
        (! empty($id)) ?: $id = uniqid('discovery-', true);

        parent::__construct(
            $id,
            $dataSource,
            $parameters,
            false,
            null,
            $jobs,
            $metrics
        );
    }

    /**
     * This method must be used to compare this object with a collect stored on database.
     * @param DataSourceCollect $entity
     * @return bool
     */
    public function equals($entity): bool
    {
        return ((($entity->getDataSource()->getName() <=> $this->getDataSource()->getName()) == 0)
            && (($entity->getParametersAsArray() <=> $this->getParameters()->toArray()) == 0));
    }
}
