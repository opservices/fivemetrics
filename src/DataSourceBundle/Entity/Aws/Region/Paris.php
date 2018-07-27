<?php

namespace DataSourceBundle\Entity\Aws\Region;

/**
 * Class SaoPaulo
 */
final class Paris extends RegionAbstract
{
    public function __construct()
    {
        parent::__construct('eu-west-3', 'EU (Paris)');
    }
}
