<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 8/8/17
 * Time: 3:13 PM
 */

namespace DataSourceBundle\Collection\Aws\EBS\Volume;

use DataSourceBundle\Entity\Aws\EBS\Volume\Volume;
use EssentialsBundle\Collection\TypedCollectionAbstract;

/**
 * Class VolumeCollection
 * @package DataSourceBundle\Collection\Aws\EBS\Volume
 */
class VolumeCollection extends TypedCollectionAbstract
{
    /**
     * @return string
     */
    public function getClass(): string
    {
        return Volume::class;
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
