<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 22/06/17
 * Time: 15:35
 */

namespace FrontendBundle\Doctrine;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use EssentialsBundle\Entity\Account\Account;

/**
 * Class AccountUidListener
 * @package FrontendBundle\Doctrine
 */
class AccountUidListener implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            'prePersist',
        ];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        /** @var Account $entity */
        $entity = $args->getEntity();
        if (! $this->isGenAccountUid($entity)) {
            return;
        }

        $entity->setUid(sha1(uniqid(rand(), true)));
    }

    /**
     * @param $entity
     * @return bool
     */
    public function isGenAccountUid($entity): bool
    {
        return (($entity instanceof Account)
            && (empty($entity->getUid())));
    }
}
