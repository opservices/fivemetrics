<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 26/09/17
 * Time: 09:12
 */

namespace GearmanBundle\Collection\Job;

use EssentialsBundle\Collection\TypedCollectionAbstract;
use GearmanBundle\Job\Job;

class JobCollection extends TypedCollectionAbstract
{
    public function getClass(): string
    {
        return Job::class;
    }

    protected function onChanged($added = null, $removed = null)
    {
        // TODO: Implement onChanged() method.
    }
}
