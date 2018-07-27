<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 03/03/17
 * Time: 09:49
 */

namespace DataSourceBundle\Gearman\Queue\Collector\Generic;

use GearmanBundle\Job\JobInterface;

/**
 * Interface ProcessorInterface
 * @package DataSourceBundle\Gearman\Queue\Collector\Generic
 */
interface ProcessorInterface
{
    /**
     * @param $data
     * @return ProcessorInterface
     */
    public function setJob(JobInterface $data): ProcessorInterface;

    /**
     * @param ResultSet $resultSet
     * @return ResultSet
     */
    public function process(ResultSet $resultSet): ResultSet;
}
