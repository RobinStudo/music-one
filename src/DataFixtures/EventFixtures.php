<?php
namespace App\DataFixtures;

use App\Entity\Event;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EventFixtures extends AbstractFixtures implements DependentFixtureInterface
{
    protected function getType(): string
    {
        return Event::class;
    }

    protected function getFile(): string
    {
        return 'event';
    }

    protected function getReferenceKey($entity): string
    {
        return $entity->getName();
    }

    protected function hookStartAt($entity,$data)
    {
        $startAt = new DateTimeImmutable($data);
        $entity->setStartAt($startAt);
    }

    protected function hookEndAt($entity,$data)
    {
        $endAt = new DateTimeImmutable($data);
        $entity->setEndAt($endAt);
    }

    protected function hookCategory($entity, $data)
    {
        $key = sprintf('%s_%s', 'category', $data);
        $category = $this->getReference($key);
        $entity->setCategory($category);
    }

    protected function hookPlace($entity, $data)
    {
        $key = sprintf('%s_%s', 'place', $data);
        $place = $this->getReference($key);
        $entity->setPlace($place);
    }

    protected function hookOwner($entity, $data)
    {
        $key = sprintf('%s_%s', 'user', $data);
        $owner = $this->getReference($key);
        $entity->setOwner($owner);
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
            PlaceFixtures::class,
            UserFixtures::class
        ];
    }
}
