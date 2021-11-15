<?php
namespace App\DataFixtures;

use App\Entity\Tag;

class TagFixtures extends AbstractFixtures
{
    protected function getType(): string
    {
        return Tag::class;
    }

    protected function getFile(): string
    {
        return 'tag';
    }

    protected function getReferenceKey($entity): string
    {
        return $entity->getName();
    }
}
