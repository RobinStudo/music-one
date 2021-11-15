<?php
namespace App\DataFixtures;

use App\Entity\User;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends AbstractFixtures
{
    private $hasher;

    public function __construct(ParameterBagInterface $parameterBag, UserPasswordHasherInterface $hasher)
    {
        parent::__construct($parameterBag);
        $this->hasher = $hasher;
    }

    protected function getType(): string
    {
        return User::class;
    }

    protected function getFile(): string
    {
        return 'user';
    }

    protected function hookPassword($entity, $data)
    {
        $hashed = $this->hasher->hashPassword($entity, $data);
        $entity->setPassword($hashed);
    }

    protected function hookStatus($entity, $data)
    {
        $constant = sprintf('%s::%s', User::class, $data);
        $status = constant($constant);
        $entity->setStatus($status);
    }

    protected function getReferenceKey($entity): string
    {
        return $entity->getEmail();
    }
}
