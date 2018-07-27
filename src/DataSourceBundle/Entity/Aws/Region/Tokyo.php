<?php

namespace DataSourceBundle\Entity\Aws\Region;

/**
 * Class Tokyo
 */
final class Tokyo extends RegionAbstract
{
    public function __construct()
    {
        parent::__construct('ap-northeast-1', 'Asia Pacific (Tokyo)');
    }
}
