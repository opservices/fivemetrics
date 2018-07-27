<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 16/04/18
 * Time: 14:08
 */

namespace EssentialsBundle\Collection\Metric;

use EssentialsBundle\Collection\TypedCollectionAbstract;
use EssentialsBundle\Entity\Metric\RealTimeData;

class RealTimeDataCollection extends TypedCollectionAbstract
{
    public function getClass(): string
    {
        return RealTimeData::class;
    }
}
