<?php

namespace DataSourceBundle\Entity\Aws\Region;

/**
 * Class SaoPaulo
 */
final class SaoPaulo extends RegionAbstract
{
    public function __construct()
    {
        parent::__construct('sa-east-1', 'South America (São Paulo)');
    }
}
