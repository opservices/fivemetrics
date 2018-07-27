<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 17/01/18
 * Time: 10:35
 */

namespace DataSourceBundle\Doctrine;

use DataSourceBundle\Entity\DataSource\DataSourceCollect;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;

/**
 * Class DataSourceCollectUidListener
 * @package DataSourceBundle\Doctrine
 */
class DataSourceCollectUidListener implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            'prePersist',
        ];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        /** @var DataSourceCollect $entity */
        $entity = $args->getEntity();
        if (! $this->isGenCollectUid($entity)) {
            return;
        }

        $entity->setUid(sha1(uniqid(rand(), true) . microtime(true)));
    }

    protected function isGenCollectUid($entity)
    {
        /** @var DataSourceCollect $entity */
        return (($entity instanceof DataSourceCollect)
            && (empty($entity->getUid())));
    }
}
