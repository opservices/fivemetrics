<?php

namespace DataSourceBundle\Entity\Aws\Region;

/**
 * Class Virginia
 */
final class Virginia extends RegionAbstract
{
    public function __construct()
    {
        parent::__construct('us-east-1', 'US East (N. Virginia)');
    }
}
