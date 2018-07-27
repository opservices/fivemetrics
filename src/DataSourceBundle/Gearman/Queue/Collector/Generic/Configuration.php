<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 09/03/17
 * Time: 18:31
 */

namespace DataSourceBundle\Gearman\Queue\Collector\Generic;

use DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\ConfigurationCollection;
use DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Configuration as ProcessorConfiguration;

/**
 * Class Configuration
 * @package DataSourceBundle\Gearman\Queue\Collector\Generic
 */
class Configuration
{
    /**
     * @param array $data
     * @return ConfigurationCollection
     */
    public static function build(array $data): ConfigurationCollection
    {
        $confs = new ConfigurationCollection();

        foreach ($data as $processor) {
            $confs->add(new ProcessorConfiguration(
                $processor['name'],
                $processor['maxRetries']
            ));
        }

        return $confs;
    }
}
