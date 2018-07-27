<?php

namespace DataSourceBundle\Entity\Aws\Region;

/**
 * Class California
 */
final class California extends RegionAbstract
{
    public function __construct()
    {
        parent::__construct('us-west-1', 'US West (N. California)');
    }
}
