<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 8/15/17
 * Time: 10:14 AM
 */

namespace DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\EBS;

use DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\Filter as AwsFilter;

/**
 * Class Filter
 * @package DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\EBS
 */
class Filter extends AwsFilter
{
    /**
     * Filter constructor.
     * @param string $namespace
     * @param array $measurementNames
     */
    public function __construct($namespace, array $measurementNames)
    {
        parent::__construct($namespace, $measurementNames);
    }
}
