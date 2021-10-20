<?php
namespace App\DataFixtures;

use App\Entity\Category;

class CategoryFixtures extends AbstractFixtures
{
    protected function getType(): string
    {
        return Category::class;
    }

    protected function getFile(): string
    {
        return 'category';
    }
}
