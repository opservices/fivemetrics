<?php

namespace DataSourceBundle\Entity\Aws\Region;

/**
 * Class Ohio
 */
final class Ohio extends RegionAbstract
{
    public function __construct()
    {
        parent::__construct('us-east-2', 'US East (Ohio)');
    }
}
