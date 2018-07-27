<?php

namespace DataSourceBundle\Entity\Aws\Region;

/**
 * Class Seoul
 */
final class Seoul extends RegionAbstract
{
    public function __construct()
    {
        parent::__construct('ap-northeast-2', 'Asia Pacific (Seoul)');
    }
}
