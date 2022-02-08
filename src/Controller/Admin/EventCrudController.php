<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EventCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Event::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            TextField::new('description'),
            TextField::new('startAt'),
            TextField::new('endAt'),
            TextField::new('capacity'),
            TextField::new('price'),
            TextField::new('picture'),
            TextField::new('category'),
            TextField::new('place'),
            TextField::new('owner'),
            TextField::new('tags'),
            TextField::new('participants'),
        ];
    }
}
