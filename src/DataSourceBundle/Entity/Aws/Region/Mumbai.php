<?php

namespace DataSourceBundle\Entity\Aws\Region;

/**
 * Class Mumbai
 */
final class Mumbai extends RegionAbstract
{
    public function __construct()
    {
        parent::__construct('ap-south-1', 'Asia Pacific (Mumbai)');
    }
}
