<?php
namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class CategoryFixtures extends AbstractFixtures implements FixtureGroupInterface
{
    protected function getType(): string
    {
        return Category::class;
    }

    protected function getFile(): string
    {
        return 'category';
    }

    protected function getReferenceKey($entity): string
    {
        return $entity->getName();
    }

    public static function getGroups(): array
    {
        return ['category'];
    }
}
