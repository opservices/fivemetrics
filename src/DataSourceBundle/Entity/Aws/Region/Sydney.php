<?php

namespace DataSourceBundle\Entity\Aws\Region;

/**
 * Class Sydney
 */
final class Sydney extends RegionAbstract
{
    public function __construct()
    {
        parent::__construct('ap-southeast-2', 'Asia Pacific (Sydney)');
    }
}
