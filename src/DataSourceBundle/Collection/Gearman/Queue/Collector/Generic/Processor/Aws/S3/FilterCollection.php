<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 6/19/17
 * Time: 11:55 AM
 */

namespace DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\S3;

use EssentialsBundle\Collection\TypedCollectionAbstract;

/**
 * Class FilterCollection
 * @package DataSourceBundle\Collection\Gearman\Queue\Collector\Generic\Processor\Aws\S3
 */
class FilterCollection extends TypedCollectionAbstract
{

    /**
     * @return string
     */
    public function getClass(): string
    {
        return 'DataSourceBundle\Entity\Gearman\Queue\Collector\Generic\Processor\Aws\S3\Filter';
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
