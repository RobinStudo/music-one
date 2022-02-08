<?php

namespace App\Controller\Admin;

use App\Entity\Event;
use App\Form\CategoryType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CurrencyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use Symfony\Component\DomCrawler\Field\FormField;

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
            TextEditorField::new('description')->hideOnIndex(),
            DateTimeField::new('startAt'),
            DateTimeField::new('endAt'),
            IntegerField::new('capacity')->hideOnIndex(),
            MoneyField::new('price')->setCurrency('EUR')->hideOnIndex(),
            ImageField::new('picture')->setUploadDir($this->mediaService->getRepository(false))->onlyOnForms(),
            ImageField::new('picture')->setBasePath('data/')->hideOnForm(),
            AssociationField::new('category')->setFormTypeOption('choice_label', 'name'),
            AssociationField::new('tags')->setFormTypeOptions([
                'choice_label' => 'name',
                'multiple' => true,
            ])->hideOnIndex(),
            AssociationField::new('place')->setFormTypeOption('choice_label', 'label')->hideOnIndex(),
            AssociationField::new('owner')->setFormTypeOption('choice_label', 'email')->hideOnIndex(),

        ];
    }
}
