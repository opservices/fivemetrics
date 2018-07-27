<?php

namespace DataSourceBundle\Entity\Aws\Region;

/**
 * Class Oregon
 */
final class Oregon extends RegionAbstract
{
    public function __construct()
    {
        parent::__construct('us-west-2', 'US West (Oregon)');
    }
}
