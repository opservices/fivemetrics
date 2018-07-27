<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 25/09/17
 * Time: 15:22
 */

namespace CollectorBundle\Collect;

use EssentialsBundle\Collection\TypedCollectionAbstract;

class CollectBucketCollection extends TypedCollectionAbstract
{
    public function getClass(): string
    {
        return CollectBucket::class;
    }

    protected function onChanged($added = null, $removed = null)
    {
        // TODO: Implement onChanged() method.
    }
}
