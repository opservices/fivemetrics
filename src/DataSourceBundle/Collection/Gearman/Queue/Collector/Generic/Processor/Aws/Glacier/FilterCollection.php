<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 7/27/17
 * Time: 2:25 PM
 */

namespace DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier;

use EssentialsBundle\Collection\TypedCollectionAbstract;

/**
 * Class FilterCollection
 * @package DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier
 */
class FilterCollection extends TypedCollectionAbstract
{

    /**
     * @return string
     */
    public function getClass(): string
    {
        return 'DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\Glacier\Filter';
    }

    /**
     * @param null $added
     * @param null $removed
     * @return mixed
     */
    protected function onChanged($added = null, $removed = null)
    {
    }
}
