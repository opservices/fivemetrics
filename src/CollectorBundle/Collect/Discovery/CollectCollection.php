<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 25/10/17
 * Time: 08:31
 */

namespace CollectorBundle\Collect\Discovery;

class CollectCollection extends \CollectorBundle\Collect\CollectCollection
{
    /**
     * @inheritDoc
     */
    public function getClass(): string
    {
        return Collect::class;
    }
}
