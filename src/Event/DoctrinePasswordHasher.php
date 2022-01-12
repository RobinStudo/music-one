<?php
namespace App\Event;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DoctrinePasswordHasher implements EventSubscriberInterface
{
    private UserPasswordHasherInterface $hasher;

    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $this->hashPassword($args->getEntity());
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $this->hashPassword($args->getEntity());
    }

    private function hashPassword(User $user): void
    {
        if($user->getPlainPassword()){
            $hashed = $this->hasher->hashPassword($user, $user->getPlainPassword());
            $user->setPassword($hashed);
        }
    }
}