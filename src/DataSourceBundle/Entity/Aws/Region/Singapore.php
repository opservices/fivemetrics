<?php

namespace DataSourceBundle\Entity\Aws\Region;

/**
 * Class Singapore
 */
final class Singapore extends RegionAbstract
{
    public function __construct()
    {
        parent::__construct('ap-southeast-1', 'Asia Pacific (Singapore)');
    }
}
