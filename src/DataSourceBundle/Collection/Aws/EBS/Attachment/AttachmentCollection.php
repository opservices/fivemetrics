<?php
/**
 * Created by PhpStorm.
 * User: fontans
 * Date: 8/8/17
 * Time: 2:14 PM
 */

namespace DataSourceBundle\Collection\Aws\EBS\Attachment;

use DataSourceBundle\Entity\Aws\EBS\Attachment\Attachment;
use EssentialsBundle\Collection\TypedCollectionAbstract;

class AttachmentCollection extends TypedCollectionAbstract
{
    /**
     * @return string
     */
    public function getClass(): string
    {
        return Attachment::class;
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