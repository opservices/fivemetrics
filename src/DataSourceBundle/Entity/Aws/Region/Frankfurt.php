<?php

namespace DataSourceBundle\Entity\Aws\Region;

/**
 * Class Frankfurt
 */
final class Frankfurt extends RegionAbstract
{
    public function __construct()
    {
        parent::__construct('eu-central-1', 'EU (Frankfurt)');
    }
}
