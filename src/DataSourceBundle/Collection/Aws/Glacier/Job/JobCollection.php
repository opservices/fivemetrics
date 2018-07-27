<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 7/26/17
 * Time: 3:18 PM
 */

namespace DataSourceBundle\Collection\Aws\Glacier\Job;

use DataSourceBundle\Entity\Aws\Glacier\Job\Job;
use EssentialsBundle\Collection\TypedCollectionAbstract;

/**
 * Class JobCollection
 * @package DataSourceBundle\Collection\Aws\Glacier\Job
 */
class JobCollection extends TypedCollectionAbstract
{
    /**
     * @return string
     */
    public function getClass(): string
    {
        return Job::class;
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
