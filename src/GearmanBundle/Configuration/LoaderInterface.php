<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 23/02/17
 * Time: 08:45
 */

namespace GearmanBundle\Configuration;

use GearmanBundle\Entity\Configuration as GearmanConfiguration;

/**
 * Interface LoaderInterface
 * @package GearmanBundle\Configuration
 */
interface LoaderInterface
{
    /**
     * @return GearmanConfiguration
     */
    public function load(): GearmanConfiguration;
}
