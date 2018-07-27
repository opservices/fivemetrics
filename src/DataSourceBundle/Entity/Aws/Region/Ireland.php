<?php

namespace DataSourceBundle\Entity\Aws\Region;

/**
 * Class Ireland
 */
final class Ireland extends RegionAbstract
{
    public function __construct()
    {
        parent::__construct('eu-west-1', 'EU (Ireland)');
    }
}
