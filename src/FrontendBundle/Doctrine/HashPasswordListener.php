<?php
/**
 * Created by PhpStorm.
 * User: fivemetrics
 * Date: 20/06/17
 * Time: 14:27
 */

namespace FrontendBundle\Doctrine;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use EssentialsBundle\Entity\Account\Account;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class HashPasswordListener
 * @package EssentialsBundle\Doctrine
 */
class HashPasswordListener implements EventSubscriber
{
    /**
     * @var UserPasswordEncoderInterface
     */
    protected $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (! $this->isNeedEncodePassword($entity)) {
            return;
        }

        $this->encodePassword($entity);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (! $this->isNeedEncodePassword($entity)) {
            return;
        }

        $this->encodePassword($entity);
        $em = $args->getEntityManager();
        $meta = $em->getClassMetadata(get_class($entity));
        $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
    }

    public function getSubscribedEvents()
    {
        return [
            'prePersist',
            'preUpdate',
        ];
    }

    /**
     * @param Account $entity
     */
    public function encodePassword(Account $entity): void
    {
        $encoded = $this->passwordEncoder->encodePassword(
            $entity,
            $entity->getPlainPassword()
        );

        $entity->setPassword($encoded);
    }

    /**
     * @param $entity
     * @return bool
     */
    public function isNeedEncodePassword($entity): bool
    {
        return $entity instanceof Account && ! empty($entity->getPlainPassword());
    }
}
