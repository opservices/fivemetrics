<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 8/15/17
 * Time: 10:12 AM
 */

namespace DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\EBS;

use DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\EBS\Filter;
use EssentialsBundle\Collection\TypedCollectionAbstract;

/**
 * Class FilterCollection
 * @package DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\EBS
 */
class FilterCollection extends TypedCollectionAbstract
{

    public function getClass(): string
    {
        return Filter::class;
    }

    protected function onChanged($added = null, $removed = null)
    {
    }
}
