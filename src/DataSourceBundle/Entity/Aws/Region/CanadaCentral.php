<?php

namespace DataSourceBundle\Entity\Aws\Region;

/**
 * Class CanadaCentral
 */
final class CanadaCentral extends RegionAbstract
{
    public function __construct()
    {
        parent::__construct('ca-central-1', 'Canada (Central)');
    }
}
