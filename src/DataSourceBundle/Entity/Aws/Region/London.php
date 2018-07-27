<?php

namespace DataSourceBundle\Entity\Aws\Region;

/**
 * Class London
 */
final class London extends RegionAbstract
{
    public function __construct()
    {
        parent::__construct('eu-west-2', 'EU (London)');
    }
}
