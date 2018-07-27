<?php

namespace DataSourceBundle\Entity\Aws\Region;

/**
 * interface Region
 */
interface RegionInterface
{
    /**
     * @return mixed
     */
    public function getCode();

    /**
     * @return mixed
     */
    public function getName();
}
